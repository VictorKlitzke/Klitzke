import pdfplumber
from db_connection import connect  # Importar a função de conexão

# Função para extrair dados do PDF
def extract_pdf_data(pdf_path):
    with pdfplumber.open(pdf_path) as pdf:
        first_page = pdf.pages[0]
        text = first_page.extract_text()

        # Dividir o texto em linhas
        lines = text.split('\n')

        # Filtrando algumas informações da nota fiscal de exemplo
        produto, valor_unitario, valor_total = None, None, None
        for line in lines:
            if "Descrição DO PRODUTO" in line:
                produto = line.split()[-1]  # Exemplo de extração
            if "Vlr. Unitário" in line:
                valor_unitario = line.split()[-1]
            if "Vl. Total" in line:
                valor_total = line.split()[-1]

        # Verificar se todas as variáveis foram preenchidas antes de retornar
        if produto and valor_unitario and valor_total:
            return {
                "produto": produto,
                "valor_unitario": valor_unitario,
                "valor_total": valor_total
            }
        else:
            return None

# Função para cadastrar no banco de dados
def insert_into_database(data):
    # Usar a conexão do arquivo db_connection
    connection = connect()
    if connection is None:
        print("Conexão com o banco falhou.")
        return

    cursor = connection.cursor()

    # Inserir dados na tabela products
    query = """
    INSERT INTO products (produto, valor_unitario, valor_total) 
    VALUES (%s, %s, %s)
    """
    values = (data['produto'], data['valor_unitario'], data['valor_total'])
    
    cursor.execute(query, values)
    connection.commit()
    
    # Fechar conexão
    cursor.close()
    connection.close()

# Caminho do PDF
pdf_path = '/mnt/data/nota-fiscal-notebook-dell.pdf'

# Extraindo dados do PDF
data = extract_pdf_data(pdf_path)

# Verificar se os dados foram extraídos corretamente
if data:
    # Inserindo os dados no banco de dados
    insert_into_database(data)
    print("Dados inseridos com sucesso!")
else:
    print("Falha ao extrair dados do PDF.")
