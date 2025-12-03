# seed_sitemusica_v2.py
from airflow import DAG
from airflow.operators.python import PythonOperator
from datetime import datetime, timedelta
import mysql.connector
import pymysql # O PyMySQL é importado aqui
from faker import Faker
import random
import string
from typing import List

# --- MUDANÇA CRUCIAL PARA COMPATIBILIDADE ---
# Registra o PyMySQL para ser usado como o conector MySQL padrão
pymysql.install_as_MySQLdb()
# ---------------------------------------------

fake = Faker("pt_BR")

# --- CONFIGURAÇÃO ---
MYSQL_HOST = "mysql-db"
MYSQL_USER = "musik"
MYSQL_PASSWORD = "musik"
MYSQL_DB = "sitemusica"

TOTAL_USUARIOS = 1000
TOTAL_MUSICAS = 500
BATCH_SIZE = 500  # ajuste se quiser

# --- HELPERS ---


def get_db_connection():
    # --- MUDANÇA AQUI: Usa pymysql.connect em vez de mysql.connector.connect ---
    return pymysql.connect(
        host=MYSQL_HOST,
        user=MYSQL_USER,
        password=MYSQL_PASSWORD,
        database=MYSQL_DB,
        autocommit=False
    )
# ----------------------------------------------------------------------------


def chunked_iterable(iterable, size):
    """Gera fatias de um iterável (lista) sem carregar tudo na memória de uma vez (útil para lists)."""
    for i in range(0, len(iterable), size):
        yield iterable[i:i + size]


def batch_insert(cursor, query, rows, batch_size=BATCH_SIZE):
    """Executa inserções em batches usando executemany para evitar listas gigantes."""
    for batch in chunked_iterable(rows, batch_size):
        cursor.executemany(query, batch)


def get_last_n_ids(table: str, id_col: str, n: int) -> List[int]:
    """Retorna os últimos n ids da tabela em ordem crescente."""
    # A função get_db_connection agora usa pymysql
    db = get_db_connection() 
    cursor = db.cursor()
    try:
        cursor.execute(f"SELECT {id_col} FROM {table} ORDER BY {id_col} DESC LIMIT %s", (n,))
        rows = cursor.fetchall()
        ids = [r[0] for r in rows][::-1]  # retorna em ordem crescente
        return ids
    finally:
        cursor.close()
        db.close()


# -------------------------
# Funções de seed (cada task)
# -------------------------

def seed_usuarios(**context):
    print("🔌 seed_usuarios: Conectando ao banco MySQL...")
    db = get_db_connection()
    cursor = db.cursor()
    try:
        usuario_query = """
        INSERT INTO Usuario
        (nome, email, biografia, senha, minha_music, data_ini, data_nasc, foto)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        # Gerar em batches e inserir imediatamente evitando listas gigantes
        total = TOTAL_USUARIOS
        batch_size = BATCH_SIZE

        generated = 0
        while generated < total:
            current_batch = []
            to_generate = min(batch_size, total - generated)
            for _ in range(to_generate):
                nome = fake.name()
                email = fake.unique.email()
                biografia = fake.text().replace("\n", " ")
                senha = fake.password()
                minha_music = fake.word()
                data_ini = fake.date_between(start_date="-5y", end_date="today")
                data_nasc = fake.date_between(start_date="-60y", end_date="-18y")
                foto = f"https://picsum.photos/seed/{random.randint(1,999999)}/200"
                current_batch.append((nome, email, biografia, senha, minha_music, data_ini, data_nasc, foto))

            cursor.executemany(usuario_query, current_batch)
            db.commit()
            generated += len(current_batch)
            print(f"  → Inseridos usuários: {generated}/{total}")

        print(f"✔ seed_usuarios: Finalizado. Inseridos {generated} usuários.")
    except Exception as e:
        db.rollback()
        print("ERRO em seed_usuarios:", e)
        raise
    finally:
        cursor.close()
        db.close()


def seed_musicas(**context):
    print("🔌 seed_musicas: Conectando ao banco MySQL...")
    db = get_db_connection()
    cursor = db.cursor()
    try:
        musicas_query = """
        INSERT INTO Musica (genero, data, artista)
        VALUES (%s, %s, %s)
        """
        generos = ["Rock", "Pop", "Sertanejo", "MPB", "Choro", "Jazz", "Rap", "Forró", "Eletrônica"]
        total = TOTAL_MUSICAS
        batch_size = BATCH_SIZE

        generated = 0
        while generated < total:
            current_batch = []
            to_generate = min(batch_size, total - generated)
            for _ in range(to_generate):
                genero = random.choice(generos)
                data = fake.date_between(start_date="-20y", end_date="today")
                artista = fake.name()
                current_batch.append((genero, data, artista))

            cursor.executemany(musicas_query, current_batch)
            db.commit()
            generated += len(current_batch)
            print(f"  → Inseridos músicas: {generated}/{total}")

        print(f"✔ seed_musicas: Finalizado. Inseridos {generated} músicas.")
    except Exception as e:
        db.rollback()
        print("ERRO em seed_musicas:", e)
        raise
    finally:
        cursor.close()
        db.close()


def seed_relacoes(**context):
    print("🔌 seed_relacoes: Conectando ao banco MySQL...")
    db = get_db_connection()
    cursor = db.cursor()
    try:
        # Pega os IDs reais gerados (caso já existam outros registros)
        user_ids = get_last_n_ids("Usuario", "idUsuario", TOTAL_USUARIOS)
        musica_ids = get_last_n_ids("Musica", "idMusica", TOTAL_MUSICAS)
        if not user_ids or not musica_ids:
            raise RuntimeError("Não encontrou usuários ou músicas suficientes para criar relações. Verifique se as etapas anteriores rodaram com sucesso.")

        rel_query = """
        INSERT INTO Usuario_das_Musica (Usuario_idUsuario, Musica_idMusica)
        VALUES (%s, %s)
        """
        batch = []
        inserted = 0

        for user_id in user_ids:
            qtd = random.randint(5, 25)
            # pega músicas aleatórias dentre os ids existentes
            musicas_escolhidas = random.sample(musica_ids, min(qtd, len(musica_ids)))
            for m in musicas_escolhidas:
                batch.append((user_id, m))
                if len(batch) >= BATCH_SIZE:
                    cursor.executemany(rel_query, batch)
                    db.commit()
                    inserted += len(batch)
                    print(f"  → Relações inseridas: {inserted}")
                    batch = []

        # inserir o que restou
        if batch:
            cursor.executemany(rel_query, batch)
            db.commit()
            inserted += len(batch)
            print(f"  → Relações inseridas: {inserted}")

        print(f"✔ seed_relacoes: Finalizado. Inseridas {inserted} relações usuário-música.")
    except Exception as e:
        db.rollback()
        print("ERRO em seed_relacoes:", e)
        raise
    finally:
        cursor.close()
        db.close()


def seed_composicoes(**context):
    print("🔌 seed_composicoes: Conectando ao banco MySQL...")
    db = get_db_connection()
    cursor = db.cursor()
    try:
        user_ids = get_last_n_ids("Usuario", "idUsuario", TOTAL_USUARIOS)
        if not user_ids:
            raise RuntimeError("Não encontrou usuários suficientes para criar composições.")

        comp_query = """
        INSERT INTO composicao (titulo, composicaocol, Usuario_idUsuario, Documento)
        VALUES (%s, %s, %s, %s)
        """

        batch = []
        inserted = 0
        for user_id in user_ids:
            qtd = random.randint(1, 3)
            for _ in range(qtd):
                titulo = fake.word().capitalize()
                texto = fake.text().replace("\n", " ")
                documento = ("DOC-" + ''.join(random.choices(string.ascii_letters, k=30))).encode()
                batch.append((titulo, texto, user_id, documento))
                if len(batch) >= BATCH_SIZE:
                    cursor.executemany(comp_query, batch)
                    db.commit()
                    inserted += len(batch)
                    print(f"  → Composições inseridas: {inserted}")
                    batch = []

        if batch:
            cursor.executemany(comp_query, batch)
            db.commit()
            inserted += len(batch)
            print(f"  → Composições inseridas: {inserted}")

        print(f"✔ seed_composicoes: Finalizado. Inseridas {inserted} composições.")
    except Exception as e:
        db.rollback()
        print("ERRO em seed_composicoes:", e)
        raise
    finally:
        cursor.close()
        db.close()


def seed_amizades(**context):
    print("🔌 seed_amizades: Conectando ao banco MySQL...")
    db = get_db_connection()
    cursor = db.cursor()
    try:
        user_ids = get_last_n_ids("Usuario", "idUsuario", TOTAL_USUARIOS)
        if not user_ids:
            raise RuntimeError("Não encontrou usuários suficientes para criar amizades.")

        amigos_query = """
        INSERT INTO amigos (idUsuario, idAmigo)
        VALUES (%s, %s)
        """

        batch = []
        inserted = 0
        for user_id in user_ids:
            # Garante que não escolha o próprio user e que haja variedade
            qtd = random.randint(10, 50)
            potencial_amigos = [uid for uid in user_ids if uid != user_id]
            if not potencial_amigos:
                continue
            amigos_lista = random.sample(potencial_amigos, min(qtd, len(potencial_amigos)))
            for amigo in amigos_lista:
                batch.append((user_id, amigo))
                if len(batch) >= BATCH_SIZE:
                    cursor.executemany(amigos_query, batch)
                    db.commit()
                    inserted += len(batch)
                    print(f"  → Amizades inseridas: {inserted}")
                    batch = []

        if batch:
            cursor.executemany(amigos_query, batch)
            db.commit()
            inserted += len(batch)
            print(f"  → Amizades inseridas: {inserted}")

        print(f"✔ seed_amizades: Finalizado. Inseridas {inserted} amizades.")
    except Exception as e:
        db.rollback()
        print("ERRO em seed_amizades:", e)
        raise
    finally:
        cursor.close()
        db.close()


# =========================
#  DAG DO AIRFLOW
# =========================

default_args = {
    "owner": "Gabriel",
    "retries": 1,
    "retry_delay": timedelta(minutes=2)
}

with DAG(
    dag_id="seed_sitemusica_v2",
    default_args=default_args,
    start_date=datetime(2025, 1, 1),
    schedule_interval=None,      # só roda manualmente
    catchup=False,
    max_active_runs=1,
    tags=["seed", "sitemusica"]
):

    t_seed_usuarios = PythonOperator(
        task_id="seed_usuarios",
        python_callable=seed_usuarios,
        provide_context=True
    )

    t_seed_musicas = PythonOperator(
        task_id="seed_musicas",
        python_callable=seed_musicas,
        provide_context=True
    )

    t_seed_relacoes = PythonOperator(
        task_id="seed_relacoes",
        python_callable=seed_relacoes,
        provide_context=True
    )

    t_seed_composicoes = PythonOperator(
        task_id="seed_composicoes",
        python_callable=seed_composicoes,
        provide_context=True
    )

    t_seed_amizades = PythonOperator(
        task_id="seed_amizades",
        python_callable=seed_amizades,
        provide_context=True
    )

    # Orquestração
    t_seed_usuarios >> t_seed_musicas >> t_seed_relacoes >> t_seed_composicoes >> t_seed_amizades