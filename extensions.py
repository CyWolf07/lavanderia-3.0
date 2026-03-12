from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager
from flask_bcrypt import Bcrypt

# ==============================
# BASE DE DATOS
# ==============================

db = SQLAlchemy()

# ==============================
# LOGIN MANAGER
# ==============================

login_manager = LoginManager()

# ruta a donde redirige si no hay sesión
login_manager.login_view = "auth.login"

# mensaje cuando intenta entrar sin login
login_manager.login_message = "Debe iniciar sesión primero."

# ==============================
# ENCRIPTAR CONTRASEÑAS
# ==============================

bcrypt = Bcrypt()