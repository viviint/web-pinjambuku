from flask import Flask, request
import logging

app = Flask(__name__)

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(message)s', datefmt='%Y-%m-%d %H:%M:%S')

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

    logging.info(f"🔔 [NOTIFIKASI TERKIRIM] Ke User ID {id_anggota}: {pesan}")

    return {
        "success": True,
        "message": f"Notifikasi peminjaman untuk {id_anggota} berhasil dicatat."
    }, 200

if __name__ == '__main__':
    app.run(host="0.0.0.0", port=8004, debug=True)