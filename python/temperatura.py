import serial
import matplotlib.pyplot as plt
import mysql.connector
import datetime

ser = serial.Serial('COM3', 9600) # Cambia el nombre del puerto según corresponda

temperatura_valores = []
humedad_valores = []

plt.ion()
fig, ax = plt.subplots()



# Configura los parámetros de conexión
config = {
    'user': 'u249123894_root',
    'password': 'B29hb$]cv>d',
    'host': '154.49.142.103',
    'database': 'u249123894_proyect_lot',
}

# Crea una conexión a la base de datos
conn = mysql.connector.connect(
    host="154.49.142.103",
    port=3306,
    user="u249123894_root",
    password="B29hb$]cv>d",
    database="u249123894_proyect_lot"
    )

# Crea un objeto cursor para ejecutar consultas SQL
cursor = conn.cursor()


while True:
    try:
        line = ser.readline().decode('utf-8').rstrip()
        print(line)
        if line:
            valores = line.split()
            temperatura = float(valores[1][:-2])
            humedad = float(valores[3][:-1])
            
            temperatura_valores.append(temperatura)
            humedad_valores.append(humedad)



            # Obtén la hora actual
            hora_actual = datetime.datetime.now()

            insercion = "INSERT INTO sensor_readings (temperature, humidity, created_at, updated_at) VALUES ( %s, %s, %s, %s)"

            # Define los valores que deseas insertar en las columnas
            valores = (temperatura, humedad, hora_actual, hora_actual)

            # Ejecuta la consulta de inserción
            cursor.execute(insercion, valores)
            
            ax.clear()
            ax.plot(temperatura_valores, label='Temperatura (C)')
            ax.plot(humedad_valores, label='Humedad (%)')
            ax.legend()
            plt.title("Temperatura y Humedad")
            plt.draw()
            plt.pause(0.1)

            conn.commit()
    except KeyboardInterrupt:
        break
        
cursor.close()
conn.close()
ser.close()
