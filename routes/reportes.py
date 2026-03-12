from flask import Blueprint, render_template

bp = Blueprint("reportes", __name__, url_prefix="/reportes")

@bp.route("/")
def index():
    return render_template("reportes.html")