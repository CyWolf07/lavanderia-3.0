# routes/reportes.py
from flask import Blueprint, render_template

bp = Blueprint("reportes", __name__)

@bp.route("/reportes")
def index():
    return render_template("reportes.html")