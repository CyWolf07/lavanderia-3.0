from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_user, logout_user, login_required
from extensions import db
from models import Usuario, Rol

bp = Blueprint("auth", __name__)

@bp.route("/login", methods=["GET", "POST"])
def login():

    if request.method == "POST":
        email = request.form["email"]
        password = request.form["password"]

        usuario = Usuario.query.filter_by(email=email).first()

        if usuario and usuario.password == password:
            login_user(usuario)
            return redirect(url_for("produccion.dashboard"))
        else:
            flash("Credenciales incorrectas", "danger")

    return render_template("login.html")


@bp.route("/logout")
@login_required
def logout():
    logout_user()
    return redirect(url_for("auth.login"))


@bp.route("/register", methods=["GET", "POST"])
def register():

    if request.method == "POST":

        nombre = request.form["nombre"]
        email = request.form["email"]
        password = request.form["password"]

        existente = Usuario.query.filter_by(email=email).first()

        if existente:
            flash("El correo ya está registrado", "warning")
            return redirect(url_for("auth.register"))

        rol = Rol.query.filter_by(nombre="Operario").first()

        if not rol:
            rol = Rol(nombre="Operario")
            db.session.add(rol)
            db.session.commit()

        nuevo_usuario = Usuario(
            nombre=nombre,
            email=email,
            password=password,
            rol_id=rol.id
        )

        db.session.add(nuevo_usuario)
        db.session.commit()

        flash("Usuario creado correctamente", "success")

        return redirect(url_for("auth.login"))

    return render_template("register.html")