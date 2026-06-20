from flask import Flask, request
from flask_sqlalchemy import SQLAlchemy
import logging
import time
import os

app = Flask(__name__)

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(message)s', datefmt='%Y-%m-%d %H:%M:%S')

db_user = os.getenv("DB_USERNAME")
db_pass = os.getenv("DB_PASSWORD")
db_host = os.getenv("DB_HOST")
db_port = os.getenv("DB_PORT")
db_name = os.getenv("DB_DATABASE")

app.config["SQLALCHEMY_DATABASE_URI"] = (
f"mysql+pymysql://{db_user}:{db_pass}@{db_host}:{db_port}/{db_name}"
)
app.config["SQLALCHEMY_TRACK_MODIFICATIONS"] = False

db = SQLAlchemy(app)

class Notification(db.Model):
    __tablename__ = "notifications"

    id = db.Column(db.Integer, primary_key=True)
    id_anggota = db.Column(db.Integer, nullable=False)
    pesan = db.Column(db.Text, nullable=False)
    created_at = db.Column(
        db.DateTime,
        server_default=db.func.now()
    )

with app.app_context():
    for i in range(10):
        try:
            db.create_all()
            print("Database connected!")
            break
        except Exception as e:
            print(f"Waiting for database... ({i+1}/10)")
            time.sleep(3)


@app.route('/api/notifications/send', methods=['POST'])
def send_notification():
    data = request.get_json()
    
    id_anggota = data.get('id_anggota')
    pesan = data.get('pesan')

    # Validasi data
    if not id_anggota or not pesan:
        return {
            "success": False,
            "message": "Data tidak lengkap. Permintaan gagal diproses!"
        }, 422  

    notif = Notification(
        id_anggota=id_anggota,
        pesan=pesan
    )

    db.session.add(notif)
    db.session.commit()

    logging.info(f"🔔 [NOTIFIKASI TERKIRIM] Ke User ID {id_anggota}: {pesan}")

    return {
        "success": True,
        "message": f"Notifikasi peminjaman untuk {id_anggota} berhasil dicatat."
    }, 200


@app.route('/api/notifications', methods=['GET'])
def get_notifications():

    notifications = Notification.query.all()

    result = []

    for n in notifications:
        result.append({
            "id": n.id,
            "id_anggota": n.id_anggota,
            "pesan": n.pesan,
            "created_at": n.created_at.strftime("%Y-%m-%d %H:%M:%S")
        })

    return {
        "success": True,
        "data": result
    }, 200

@app.route('/api/notifications/user/<int:id_anggota>', methods=['GET'])
def get_notification_by_user(id_anggota):

    notifications = Notification.query.filter_by(
        id_anggota=id_anggota
    ).all()

    result = []

    for n in notifications:
        result.append({
            "id": n.id,
            "id_anggota": n.id_anggota,
            "pesan": n.pesan,
            "created_at": n.created_at.strftime("%Y-%m-%d %H:%M:%S")
        })

    return {
        "success": True,
        "data": result
    }, 200

if __name__ == '__main__':
    app.run(host="0.0.0.0", port=8004, debug=True)