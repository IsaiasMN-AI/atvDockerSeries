from fastapi import APIRouter, HTTPException
from typing import List

from models.series import Serie
from database.series_db import inserir_serie, listar_series, buscar_serie_por_titulo

router = APIRouter(prefix="/series")


@router.post("", response_model=Serie)
def criar_serie(serie: Serie):
    if serie.ano_lancamento <= 1900:
        raise HTTPException(status_code=400, detail="O ano de lançamento deve ser maior que 1900.")

    if serie.temporadas <= 0:
        raise HTTPException(status_code=400, detail="O número de temporadas deve ser positivo.")

    dados = serie.model_dump()

    try:
        inserir_serie(dados)
    except Exception:
        # conflito de título (PRIMARY KEY)
        raise HTTPException(status_code=400, detail="Já existe uma série com esse título.")

    return dados


@router.get("", response_model=List[Serie])
def get_series():
    return listar_series()


@router.get("/{titulo}", response_model=Serie)
def get_serie_por_titulo(titulo: str):
    serie = buscar_serie_por_titulo(titulo)

    if not serie:
        raise HTTPException(status_code=404, detail="Série não encontrada.")

    return serie
