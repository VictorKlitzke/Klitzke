from flask import Flask, request
from flask_cors import CORS
from files import extract_table_data, insert_into_database 

app = Flask(__name__)
CORS(app)

@app.route('/upload', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        return {'success': False, 'message': 'No file part'}

    file = request.files['file']
    if file.filename == '':
        return {'success': False, 'message': 'No selected file'}

    products = extract_table_data(file)  

    if not products:
        return {'success': False, 'message': 'No products found in the PDF'}

    insert_into_database(products)

    return {'success': True, 'products': products}  

if __name__ == '__main__':
    app.run(port=5000)
