import mysql.connector
from mysql.connector import Error

def connect():
  try:
    connection = mysql.connector.connect(
      host='localhost',  
      database='Klitzke',  
      user='root',  
      password='root'
    )
    if connection.is_connected():
      db_info = connection.get_server_info()
      print("Conectado ao servidor MySQL versão ", db_info)    
  except Error as e:
    print("Erro ao conectar ao MySQL", e)    
    return None 
connect()