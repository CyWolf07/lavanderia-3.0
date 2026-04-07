from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_required
from extensions import db
from models.prenda import Prenda

bp = Blueprint("prendas", __name__, url_prefix="/prendas")

@bp.route("/")
@login_required
def index():
    prendas = Prenda.query.all()
    return render_template("prendas.html", prendas=prendas)

@bp.route("/nueva", methods=["GET", "POST"])
@login_required
def nueva():
    if request.method == "POST":
        nombre = request.form["nombre"]
        tipo = request.form["tipo"]
        precio = float(request.form["precio"])

        nueva_prenda = Prenda(nombre=nombre, tipo=tipo, precio=precio)
        db.session.add(nueva_prenda)
        db.session.commit()

        flash("Prenda registrada correctamente", "success")
        return redirect(url_for("prendas.index"))

    return render_template("nueva_prenda.html")