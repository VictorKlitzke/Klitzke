import mysql.connector
from mysql.connector import Error

def connect():
  try:
    connection = mysql.connector.connect(
      host='localhost',  
      database='klitzke',  
      user='root',  
      password='root'
    )
    if connection.is_connected():
      return connection   
  except Error as e:
    print("Erro ao conectar ao MySQL", e)    
    return None 
       
connect()
