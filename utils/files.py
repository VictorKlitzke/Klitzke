import pdfplumber
import re
from services import connect  
from decimal import Decimal

def extract_quantity(qty_string):
    match = re.match(r'[\d.,]+', qty_string)
    if match:
        qty_value = match.group(0)
        return float(qty_value.replace(',', '.'))
    return 0.0

def extract_table_data(file):
    with pdfplumber.open(file) as pdf:
        first_page = pdf.pages[0]
        table = first_page.extract_table()

        products_dict = {}

        if table:
            for idx, row in enumerate(table):
                print(f"Linha {idx}: {row}")

                if not row or len(row) < 8:
                    print(f"Linha inválida: {row}")
                    continue  

                product_id_str = row[0].strip() if row[0] else None
                if not product_id_str or product_id_str == 'CÓDIGO':
                    print(f"ID inválido encontrado: {product_id_str}")
                    continue  

                try:
                    product_id = int(product_id_str)
                except ValueError:
                    print(f"ID inválido encontrado: {product_id_str}")
                    continue

                descricao = row[2].strip().replace('\n', ' ') if row[2] else ''
                vl_unitario = row[7].strip() if row[7] else ''
                quantidade = extract_quantity(row[6])

                if quantidade is None:
                    quantidade = 0

                if descricao and re.match(r'.+', descricao):
                    if product_id in products_dict:
                        products_dict[product_id]['QTDE'] += quantidade
                    else:
                        products_dict[product_id] = {
                            "id": product_id,
                            "descricao": descricao,
                            "vl_unitario": vl_unitario,
                            "QTDE": quantidade,
                        }
                    print(products_dict[product_id])
                else:
                    print(f"Produto com ID {product_id} não foi adicionado devido a descrição inválida: '{descricao}'")

        products = list(products_dict.values())

        if not products:
            print("Nenhum produto encontrado no PDF.")
            return []

        return products


def insert_into_database(products):
    connection = connect()
    if connection is None:
        print("Falha ao estabelecer a conexão.")
        return
    
    show_on_page = 0
    invoice = 'nota fiscal'

    try:
        cursor = connection.cursor()

        for product in products:
            vl_unitario = product['vl_unitario'].replace(' ', '').replace('.', '', 2).replace(',', '.').strip()
            qtde = product['QTDE'] 

            vl_unitario = vl_unitario
            qtde = float(qtde)

            check_query = "SELECT quantity FROM products WHERE id = %s"
            cursor.execute(check_query, (product['id'],))
            result = cursor.fetchone()

            if result:  
                current_quantity = result[0]
                new_quantity = current_quantity + qtde
                
                selected_query = """SELECT multiply FROM config_multiply_product ORDER BY id DESC LIMIT 1"""
                cursor.execute(selected_query)
                last_value_result = cursor.fetchone()
                vl_unitario = Decimal(vl_unitario)
                
                if last_value_result:
                    last_value = Decimal(last_value_result[0]) / Decimal(100)  
                    value_product = vl_unitario * (1 + last_value) 
                else:
                    print("Nenhum valor encontrado em config_multiply_product. Usando vl_unitario.")
                    value_product = vl_unitario 
                
                update_query = "UPDATE products SET quantity = %s, stock_quantity = %s WHERE id = %s"
                cursor.execute(update_query, (new_quantity, new_quantity, product['id']))
                print(f"Produto {product['id']} atualizado para a nova quantidade: {new_quantity}")
                
                movement_query = """
                    INSERT INTO product_movements (product_id, type, quantity, value, date, status_product) 
                    VALUES (%s, %s, %s, %s, NOW(), %s)
                """
                cursor.execute(movement_query, (product['id'], 'Entrada', qtde, vl_unitario, 'Em estoque'))
            
            else:                  
                selected_query = """SELECT multiply FROM config_multiply_product ORDER BY id DESC LIMIT 1"""
                cursor.execute(selected_query)
                last_value_result = cursor.fetchone()
                
                vl_unitario = Decimal(vl_unitario) 

                if last_value_result:
                    last_value = Decimal(last_value_result[0]) / Decimal(100)  
                    value_product = vl_unitario * (1 + last_value)  
                else:
                    print("Nenhum valor encontrado em config_multiply_product. Usando vl_unitario.")
                    value_product = vl_unitario
                    
                values = (product['descricao'], vl_unitario, value_product, qtde, qtde, show_on_page, invoice)  

                insert_query = """
                INSERT INTO products (id, name, cost_value, value_product, quantity, stock_quantity, show_on_page, invoice) 
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                """
                cursor.execute(insert_query, (product['id'],) + values)
                print(f"Produto {product['id']} inserido com sucesso.")
                
                movement_query = """
                    INSERT INTO product_movements (product_id, type, quantity, value, date, status_product) 
                    VALUES (%s, %s, %s, %s, NOW(), %s)
                """
                cursor.execute(movement_query, (product['id'], 'Entrada', qtde, vl_unitario, 'Em estoque'))
                connection.commit()
                print("Dados inseridos/atualizados com sucesso!")

        
    except Exception as e:
        print(f"Ocorreu um erro: {e}")
    
    finally:
        cursor.close()
        connection.close()