from flask import Blueprint, render_template, request, redirect, url_for
from flask_login import login_required, current_user
from models import db, Produccion, Prenda

bp = Blueprint("produccion", __name__)

@bp.route("/dashboard")
@login_required
def dashboard():
    producciones = Produccion.query.all()
    return render_template("dashboard.html", producciones=producciones)

@bp.route("/registrar", methods=["POST"])
@login_required
def registrar_produccion():
    prenda_id = request.form["prenda_id"]
    cantidad = int(request.form["cantidad"])
    produccion = Produccion(usuario_id=current_user.id, prenda_id=prenda_id, cantidad=cantidad)
    db.session.add(produccion)
    db.session.commit()
    return redirect(url_for("produccion.dashboard"))

@bp.route("/reportes")
@login_required
def reportes():
    producciones = Produccion.query.all()
    return render_template("reportes.html", producciones=producciones)