from flask import Flask, request, jsonify
import cv2
import numpy as np
import base64

app = Flask(__name__)

@app.route('/upload', methods=['POST'])
def upload_image():
    data = request.json
    image_data = data['image']
    employee_id = data['employee_id']

    # Decode the base64 image
    image = base64.b64decode(image_data)
    np_image = np.frombuffer(image, np.uint8)
    img = cv2.imdecode(np_image, cv2.IMREAD_COLOR)

    # Save the image
    image_path = f'uploads/faces/{employee_id}.png'
    cv2.imwrite(image_path, img)

    return jsonify({"status": "success", "image_path": image_path})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
