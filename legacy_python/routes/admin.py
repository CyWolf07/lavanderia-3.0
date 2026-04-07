from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_required, current_user
from extensions import db
from models import Usuario, Prenda, Produccion
from datetime import datetime

bp = Blueprint("admin", __name__, url_prefix="/admin")


# ============================
# VALIDAR SI ES ADMIN
# ============================

def es_admin():
    return current_user.rol.nombre == "admin"


# ============================
# PANEL ADMIN
# ============================

@bp.route("/panel")
@login_required
def panel_admin():

    if not es_admin():
        flash("No tienes permiso para acceder aquí", "danger")
        return redirect(url_for("produccion.dashboard"))

    return render_template("panel_admin.html")


# ============================
# LISTAR PRENDAS
# ============================

@bp.route("/prendas")
@login_required
def listar_prendas():

    if not es_admin():
        return redirect(url_for("produccion.dashboard"))

    prendas = Prenda.query.all()

    return render_template(
        "admin/prendas.html",
        prendas=prendas
    )


# ============================
# CREAR PRENDA
# ============================

@bp.route("/prendas/crear", methods=["GET", "POST"])
@login_required
def crear_prenda():

    if not es_admin():
        return redirect(url_for("produccion.dashboard"))

    if request.method == "POST":

        nombre = request.form["nombre"]
        precio = request.form["precio"]

        prenda = Prenda(
            nombre=nombre,
            precio=precio
        )

        db.session.add(prenda)
        db.session.commit()

        flash("Prenda creada correctamente", "success")

        return redirect(url_for("admin.listar_prendas"))

    return render_template("admin/crear_prenda.html")


# ============================
# EDITAR PRENDA
# ============================

@bp.route("/prendas/editar/<int:id>", methods=["GET", "POST"])
@login_required
def editar_prenda(id):

    if not es_admin():
        return redirect(url_for("produccion.dashboard"))

    prenda = Prenda.query.get_or_404(id)

    if request.method == "POST":

        prenda.nombre = request.form["nombre"]
        prenda.precio = request.form["precio"]

        db.session.commit()

        flash("Prenda actualizada", "success")

        return redirect(url_for("admin.listar_prendas"))

    return render_template(
        "admin/editar_prenda.html",
        prenda=prenda
    )


# ============================
# ELIMINAR PRENDA
# ============================

@bp.route("/prendas/eliminar/<int:id>")
@login_required
def eliminar_prenda(id):

    if not es_admin():
        return redirect(url_for("produccion.dashboard"))

    prenda = Prenda.query.get_or_404(id)

    db.session.delete(prenda)
    db.session.commit()

    flash("Prenda eliminada", "warning")

    return redirect(url_for("admin.listar_prendas"))


# ============================
# REPORTE DE PRODUCCION
# ============================

@bp.route("/reporte")
@login_required
def reporte_quincena():

    if not es_admin():
        return redirect(url_for("produccion.dashboard"))

    hoy = datetime.now()

    if hoy.day <= 15:
        inicio = datetime(hoy.year, hoy.month, 1)
        fin = datetime(hoy.year, hoy.month, 15)
    else:
        inicio = datetime(hoy.year, hoy.month, 16)
        fin = datetime(hoy.year, hoy.month, 30)

    producciones = Produccion.query.filter(
        Produccion.fecha >= inicio,
        Produccion.fecha <= fin
    ).all()

    total = sum(p.total for p in producciones)

    return render_template(
        "admin/reporte_quincena.html",
        producciones=producciones,
        total=total,
        inicio=inicio,
        fin=fin
    )


# ============================
# LISTA DE USUARIOS
# ============================

@bp.route("/usuarios")
@login_required
def listar_usuarios():

    if not es_admin():
        return redirect(url_for("produccion.dashboard"))

    usuarios = Usuario.query.all()

    return render_template(
        "admin/usuarios.html",
        usuarios=usuarios
    )       