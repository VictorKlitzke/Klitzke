import pdfplumber
import re
from services import connect  

def extract_table_data(pdf_path):
    with pdfplumber.open(pdf_path) as pdf:
        first_page = pdf.pages[0] 
        table = first_page.extract_table()

        products = []

        if table:
            for row in table:
                if row[0] and re.match(r'\d+', row[0]):  
                    product_data = {
                        "cod_prod": row[0],
                        "descricao": row[1],
                        "vl_unitario": row[7],  
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
    
    show_on_page = 0;

    try:
        cursor = connection.cursor()

        query = """
        INSERT INTO products (name, value_product, quantity, stock_quantity, show_on_page) 
        VALUES (%s, %s, %s, %s, %s)
        """
        
        print(products)

        for product in products:
            vl_unitario = product['vl_unitario'].replace(' ', '').replace('.', '', 2).replace(',', '.').strip()
            qtde = product['QTDE'].replace(' ', '').replace('.', '', 2).replace(',', '.').strip()
            
            vl_unitario = float(vl_unitario)
            qtde = float(qtde)

            values = (product['descricao'], vl_unitario, qtde, qtde, show_on_page)  # Adiciona dados à lista de valores
            cursor.execute(query, values)

        connection.commit()
        print("Dados inseridos com sucesso!")
    
    except Exception as e:
        print(f"Ocorreu um erro: {e}")
    
    finally:
        cursor.close()
        connection.close()


pdf_path = 'nota-fiscal-notebook-dell.pdf'
data = extract_table_data(pdf_path) 

if data:
    insert_into_database(data)
    print("Dados inseridos com sucesso!")
else:
    print("Falha ao extrair dados do PDF.")
