import mysql.connector

def save_recognition_result(face_id):
    connection = mysql.connector.connect(
        host='localhost',
        user='root',
        password='',
        database='karyawansi'
    )
    cursor = connection.cursor()
    query = "INSERT INTO recognized_faces (face_id) VALUES (%s)"
    cursor.execute(query, (face_id,))
    connection.commit()
    cursor.close()
    connection.close()

@app.route('/recognize', methods=['POST'])
def recognize():
    data = request.json
    img = readb64(data['image'])

    # Perform face recognition here
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, 1.1, 4)

    if len(faces) > 0:
        # Assuming you have a way to get face_id
        face_id = "some_face_id"
        save_recognition_result(face_id)
        return jsonify({"status": "success", "message": "Face detected"})
    else:
        return jsonify({"status": "failure", "message": "No face detected"})
