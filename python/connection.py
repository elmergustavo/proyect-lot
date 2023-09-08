import mysql.connector
import datetime

# Configura los parámetros de conexión
config = {
    'user': 'u249123894_tavcode',
    'password': 'jE~GSa/i4[',
    'host': '154.49.142.103',
    'database': 'u249123894_laravel',
}

# Crea una conexión a la base de datos
conn = mysql.connector.connect(
    host="154.49.142.103",
    port=3306,
    user="u249123894_root",
    password="wi]&JG|9C#",
    database="u249123894_proyect_lot"
    )

# Crea un objeto cursor para ejecutar consultas SQL
cursor = conn.cursor()

 # Define la consulta de inserción

# Obtén la hora actual
hora_actual = datetime.datetime.now()

insercion = "INSERT INTO sensor_readings (temperature, humidity, created_at, updated_at) VALUES ( %s, %s, %s, %s)"

# Define los valores que deseas insertar en las columnas
valores = ('django', 'jango', hora_actual, hora_actual)

# Ejecuta la consulta de inserción
cursor.execute(insercion, valores)

# Ejecuta una consulta SQL
# consulta = "SELECT * FROM categories"
# cursor.execute(consulta)

# Recupera los resultados de la consulta
# resultados = cursor.fetchall()

# Itera a través de los resultados
# for fila in resultados:
#     print(fila)

# Cierra el cursor y la conexión
conn.commit()
cursor.close()
conn.close()
