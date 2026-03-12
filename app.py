from flask import Flask
from extensions import db, login_manager, bcrypt
from models import Usuario, Rol, Prenda, Produccion
from routes.auth import bp as auth_bp
from routes.produccion import bp as produccion_bp
from routes.admin import bp as admin_bp
from routes.reportes import bp as reportes_bp   # nuevo
from routes.prendas import bp as prendas_bp 


app = Flask(__name__)
app.config["SECRET_KEY"] = "lavanderia_secret"
app.config["SQLALCHEMY_DATABASE_URI"] = "mysql+pymysql://root:root@localhost/lavanderia"
app.config["SQLALCHEMY_TRACK_MODIFICATIONS"] = False

db.init_app(app)
login_manager.init_app(app)
bcrypt.init_app(app)

@app.route("/")
def inicio():
    return """
    <h1>🧺 Sistema de Lavandería</h1>
    <br>
    <a href='/login'>🔐 Iniciar sesión</a>
    <br><br>
    <a href='/register'>📝 Registrarse</a>
    """

@login_manager.user_loader
def load_user(user_id):
    return Usuario.query.get(int(user_id))

# Registrar blueprints
app.register_blueprint(auth_bp)
app.register_blueprint(produccion_bp)
app.register_blueprint(admin_bp)
app.register_blueprint(reportes_bp)   # nuevo
app.register_blueprint(prendas_bp)   # nuevo


with app.app_context():
    db.create_all()

if __name__ == "__main__":
    app.run(debug=True)