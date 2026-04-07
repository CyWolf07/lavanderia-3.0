from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_required, current_user
from extensions import db
from models import Produccion, Prenda

bp = Blueprint("produccion", __name__)


# =========================
# DASHBOARD
# =========================

@bp.route("/dashboard")
@login_required
def dashboard():

    producciones = Produccion.query.filter_by(
        usuario_id=current_user.id
    ).all()

    return render_template(
        "dashboard.html",
        producciones=producciones
    )


# =========================
# REGISTRAR PRODUCCION
# =========================

@bp.route("/registrar", methods=["GET", "POST"])
@login_required
def registrar_produccion():

    prendas = Prenda.query.all()

    if request.method == "POST":

        prenda_id = request.form["prenda"]
        cantidad = int(request.form["cantidad"])

        prenda = Prenda.query.get(prenda_id)

        # CALCULO AUTOMATICO
        total = cantidad * prenda.precio

        nueva_produccion = Produccion(
            usuario_id=current_user.id,
            prenda_id=prenda_id,
            cantidad=cantidad,
            total=total
        )

        db.session.add(nueva_produccion)
        db.session.commit()

        flash("Producción registrada correctamente", "success")

        return redirect(url_for("produccion.dashboard"))

    return render_template(
        "registrar_produccion.html",
        prendas=prendas
    )