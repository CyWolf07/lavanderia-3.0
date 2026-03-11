from flask import Blueprint, render_template, request, redirect, url_for
from flask_login import login_required
from models import db, Prenda

bp = Blueprint("admin", __name__)

@bp.route("/admin/prendas")
@login_required
def listar_prendas():
    prendas = Prenda.query.all()
    return render_template("reportes.html", producciones=prendas)

@bp.route("/admin/prendas/nueva", methods=["POST"])
@login_required
def nueva_prenda():
    nombre = request.form["nombre"]
    precio = request.form["precio"]
    prenda = Prenda(nombre=nombre, precio_unitario=precio)
    db.session.add(prenda)
    db.session.commit()
    return redirect(url_for("admin.listar_prendas"))