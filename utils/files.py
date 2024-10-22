import pdfplumber
import re
from services import connect  

def extract_table_data(file):
    with pdfplumber.open(file) as pdf:
        first_page = pdf.pages[0] 
        table = first_page.extract_table()

        products = []

        if table:
            for row in table:
                if row[0] and re.match(r'\d+', row[0]):  
                    product_data = {
                        "id": row[0].strip(),  
                        "descricao": row[1].strip(),  
                        "vl_unitario": row[7].strip(),  
                        "QTDE": extract_quantity(row[6]),  
                    }
                    products.append(product_data)

        return products

def extract_quantity(qty_string):
    match = re.match(r'[\d.,]+', qty_string)
    if match:
        qty_value = match.group(0)
        return qty_value.split(',')[0].split('.')[0]  
    return '0'

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
            qtde = product['QTDE'].replace(' ', '').replace('.', '', 2).replace(',', '.').strip()
            
            vl_unitario = float(vl_unitario)
            qtde = float(qtde)

            check_query = "SELECT quantity FROM products WHERE id = %s"
            cursor.execute(check_query, (product['id'],))
            result = cursor.fetchone()

            if result:  
                current_quantity = result[0]
                new_quantity = current_quantity + qtde
                
                update_query = "UPDATE products SET quantity = %s, stock_quantity = %s WHERE id = %s"
                cursor.execute(update_query, (new_quantity, new_quantity, product['id']))
                print(f"Produto {product['id']} atualizado para a nova quantidade: {new_quantity}")
            else:  
                values = (product['descricao'], vl_unitario, qtde, qtde, show_on_page, invoice)  
                insert_query = """
                INSERT INTO products (id, name, value_product, quantity, stock_quantity, show_on_page, invoice) 
                VALUES (%s, %s, %s, %s, %s, %s, %s)
                """
                cursor.execute(insert_query, (product['id'],) + values)
                print(f"Produto {product['id']} inserido com sucesso.")

        connection.commit()
        print("Dados inseridos/atualizados com sucesso!")
    
    except Exception as e:
        print(f"Ocorreu um erro: {e}")
    
    finally:
        cursor.close()
        connection.close()
