from extensions import db
from flask_login import UserMixin
from datetime import datetime


# ==========================
# TABLA ROLES
# ==========================

class Rol(db.Model):

    __tablename__ = "roles"

    id = db.Column(db.Integer, primary_key=True)

    nombre = db.Column(db.String(50), unique=True, nullable=False)

    descripcion = db.Column(db.String(200))

    usuarios = db.relationship("Usuario", backref="rol", lazy=True)



# ==========================
# TABLA USUARIOS
# ==========================

class Usuario(UserMixin, db.Model):

    __tablename__ = "usuarios"

    id = db.Column(db.Integer, primary_key=True)

    nombre = db.Column(db.String(100), nullable=False)

    email = db.Column(db.String(120), unique=True, nullable=False)

    password = db.Column(db.String(200), nullable=False)

    rol_id = db.Column(db.Integer, db.ForeignKey("roles.id"))

    fecha_registro = db.Column(db.DateTime, default=datetime.utcnow)

    producciones = db.relationship("Produccion", backref="usuario", lazy=True)



# ==========================
# TABLA PRENDAS
# ==========================

class Prenda(db.Model):

    __tablename__ = "prendas"

    id = db.Column(db.Integer, primary_key=True)

    nombre = db.Column(db.String(100), nullable=False)

    tipo = db.Column(db.String(50))

    precio = db.Column(db.Float)

    producciones = db.relationship("Produccion", backref="prenda", lazy=True)



# ==========================
# TABLA PRODUCCION
# ==========================

class Produccion(db.Model):

    __tablename__ = "producciones"

    id = db.Column(db.Integer, primary_key=True)

    usuario_id = db.Column(
        db.Integer,
        db.ForeignKey("usuarios.id"),
        nullable=False
    )

    prenda_id = db.Column(
        db.Integer,
        db.ForeignKey("prendas.id"),
        nullable=False
    )

    cantidad = db.Column(db.Integer, nullable=False)

    fecha = db.Column(db.DateTime, default=datetime.utcnow)

    total = db.Column(db.Float)