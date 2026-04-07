# models/produccion.py
from extensions import db

class Produccion(db.Model):
    __tablename__ = "producciones"

    id = db.Column(db.Integer, primary_key=True)
    fecha = db.Column(db.Date, nullable=False)
    cantidad = db.Column(db.Integer, nullable=False)

    # Relación con Usuario
    usuario_id = db.Column(db.Integer, db.ForeignKey("usuarios.id"), nullable=False)

    def __repr__(self):
        return f"<Produccion {self.id} - {self.fecha}>"