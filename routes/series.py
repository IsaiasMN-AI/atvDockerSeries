from fastapi import APIRouter, HTTPException
from typing import List

from models.series import Serie
from database.series_db import (
    inserir_serie, listar_series, buscar_serie_por_titulo, 
    buscar_serie_por_id, atualizar_serie, deletar_serie
)

router = APIRouter(prefix="/series")

@router.post("", response_model=Serie)
def criar_serie(serie: Serie):
    if serie.ano_lancamento <= 1900:
        raise HTTPException(status_code=400, detail="O ano de lançamento deve ser maior que 1900.")
    if serie.temporadas <= 0:
        raise HTTPException(status_code=400, detail="O número de temporadas deve ser positivo.")
    
    dados = serie.model_dump(exclude={"id"}) # O id é gerado pelo banco
    try:
        inserir_serie(dados)
    except Exception:
        raise HTTPException(status_code=400, detail="Erro ao inserir série.")
    return serie

@router.get("", response_model=List[Serie])
def get_series():
    return listar_series()

@router.get("/{id_serie}", response_model=Serie)
def get_serie_por_id(id_serie: int):
    serie = buscar_serie_por_id(id_serie)
    if not serie:
        raise HTTPException(status_code=404, detail="Série não encontrada.")
    return serie

@router.patch("/{id_serie}")
def editar_serie(id_serie: int, serie: Serie):
    dados = serie.model_dump(exclude={"id"})
    atualizou = atualizar_serie(id_serie, dados)
    if not atualizou:
        raise HTTPException(status_code=404, detail="Série não encontrada para edição.")
    return {"mensagem": "Série atualizada com sucesso"}

@router.delete("/{id_serie}")
def remover_serie(id_serie: int):
    deletou = deletar_serie(id_serie)
    if not deletou:
        raise HTTPException(status_code=404, detail="Série não encontrada para exclusão.")
    return {"mensagem": "Série deletada com sucesso"}