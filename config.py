import os

class Config:
    # Clave secreta para sesiones y seguridad
    SECRET_KEY = os.environ.get("SECRET_KEY") or "clave_segura"

    # Conexión a PostgreSQL
    SQLALCHEMY_DATABASE_URI = (
        os.environ.get("DATABASE_URL")
        or "postgresql://postgres:0408@localhost:5432/inventario_db"
    )

    # Desactiva el seguimiento de modificaciones para mejorar rendimiento
    SQLALCHEMY_TRACK_MODIFICATIONS = False