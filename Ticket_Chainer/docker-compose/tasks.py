import os.path
import sys
from datetime import datetime

import requests
from dotenv import load_dotenv
from invoke import task

ROOT_DIR = os.path.dirname(__file__)

# load variables from .env file
load_dotenv()


def get_env():
    return os.getenv('ENV')


def get_compose_file():
    return 'docker-compose.prod.yml' if get_env() == 'prod' else 'docker-compose.yml'


def get_app(name):
    return get_apps()[name]


def get_apps():
    return {
        'docker-compose': {
            'git': 'gitlab.com:ticketchainer/docker-compose.git',
            'local_dir': ROOT_DIR
        }, 'api': {
            'git': 'gitlab.com:ticketchainer/api.git',
            'local_dir': os.getenv('API_APP_PATH')
        }, 'api-bundle': {
            'git': 'gitlab.com:ticketchainer/api-bundle.git',
            'local_dir': os.getenv('API_BUNDLE_APP_PATH')
        }, 'backoffice': {
            'git': 'gitlab.com:ticketchainer/backoffice.git',
            'local_dir': os.getenv('BACKOFFICE_APP_PATH')
        }, 'html2pdf': {
            'git': 'gitlab.com:ticketchainer/html2pdf.git',
            'local_dir': os.getenv('HTML2PDF_APP_PATH')
        }, 'club-frontoffice': {
            'git': 'gitlab.com:ticketchainer/club-frontoffice.git',
            'local_dir': os.getenv('CLUB_SITE_APP_PATH')
        }
    }


# Load custom dot env file
# Example: inv envfile /etc/.env.prod
@task
def envfile(ctx, env_file):
    if os.path.exists(env_file):
        print('print env file \'{}\' loaded'.format(env_file))
        load_dotenv(dotenv_path=env_file, override=True)

    else:
        print('env file not found : {}'.format(env_file))
        sys.exit()


@task(optional=['path', 'filename'])
def pgdump(ctx, path='.', filename=None):
    host = os.getenv('POSTGRES_HOST')
    port = os.getenv('POSTGRES_PORT')
    database = os.getenv('POSTGRES_DB')
    user = os.getenv('POSTGRES_USER')
    password = os.getenv('POSTGRES_PASSWORD')
    if not filename:
        filename = 'dump_{}_{}.dump'.format(database, datetime.today().strftime('%Y%m%d%H%M%S'))
    dump_file = os.path.join(path, filename)
    print('generating database dump file "{}" ... '.format(dump_file), end='', flush=True)
    ctx.run('touch {}'.format(dump_file))
    ctx.run('PGPASSWORD="{}" pg_dump -h {} -U {} -p {} -d {} -O -F c -b -v -f {}'.format(password, host, user, port,
                                                                                        database, dump_file))
    print('Dump file \'{}\' generated successfully'.format(dump_file))


@task(optional=['path'])
def pgrestore(ctx, dump_file):
    host = os.getenv('POSTGRES_HOST')
    port = os.getenv('POSTGRES_PORT')
    database = os.getenv('POSTGRES_DB')
    user = os.getenv('POSTGRES_USER')
    password = os.getenv('POSTGRES_PASSWORD')
    print('Restore data "{}" ... '.format(dump_file), end='', flush=True)
    ctx.run(
        'PGPASSWORD="{}" pg_restore -O -C -c -e -h {} -p {} -U {} -d {} -v {}'.format(password, host, port, user,
                                                                                      database,
                                                                                      dump_file))
    print('Dump file loaded successfully')

#
# @task(optional=['path', 'filename'])
# def copy_database(ctx):
#     envfile(ctx, '.env')
#     ctx.run('PGPASSWORD="{}" psql -h {} -p {} -U {} \'ticketchainer_dev\' -c "SELECT * FROM"'
#             .format(os.getenv('POSTGRES_PASSWORD'),
#                     os.getenv('POSTGRES_HOST'),
#                     os.getenv('POSTGRES_PORT'),
#                     os.getenv('POSTGRES_USER'))
#             )
#
#
#     # envfile(ctx, '.env.staging')
#     # ctx.run('PGPASSWORD="{}" pg_dump -h {} -U {} -p {} -d {} -O -F p -b -v -f {}'.format(
#     #     os.getenv('POSTGRES_PASSWORD'),
#     #     os.getenv('POSTGRES_HOST'),
#     #     os.getenv('POSTGRES_USER'),
#     #     os.getenv('POSTGRES_PORT'),
#     #     os.getenv('POSTGRES_DB'),
#     #     '/tmp/aaaaa.dump'
#     # ))
#
#     # envfile(ctx, '.env')
#     # ctx.run('PGPASSWORD="{}" pg_restore -O -C -c -e -h {} -p {} -U {} -d {} -v {}'.format(
#     #     os.getenv('POSTGRES_PASSWORD'),
#     #     os.getenv('POSTGRES_HOST'),
#     #     os.getenv('POSTGRES_PORT'),
#     #     os.getenv('POSTGRES_USER'),
#     #     os.getenv('POSTGRES_DB'),
#     #     '/tmp/aaaaa.dump'
#     # ))
#
#     # ctx.run('PGPASSWORD="{}" dropdb -h {} -p {} -U {} \'ticketchainer_dev\''
#     #         .format(os.getenv('POSTGRES_PASSWORD'),
#     #                 os.getenv('POSTGRES_HOST'),
#     #                 os.getenv('POSTGRES_PORT'),
#     #                 os.getenv('POSTGRES_USER'))
#     #         )
#

@task
def gitcloneall(ctx):
    for app in get_apps():
        gitclone(ctx, app)


@task
def gitclone(ctx, app):
    path = get_app(app)['local_dir']
    print('[git] cloning %s : ' % app, end='', flush=True)
    ctx.run('git clone git@{} {}'.format(get_app(app)['git'], path))


@task
def gitpullall(ctx):
    for app in get_apps():
        gitpull(ctx, app)


@task
def gitpull(ctx, app):
    with ctx.cd(get_app(app)['local_dir']):
        print('[git] pulling %s : ' % app, end='', flush=True)
        ctx.run('git pull -f')


@task
def composer_install(ctx, app):
    dcxcmd(ctx, 'php', 'cd {} && composer install'.format(app))


@task
def dcxcmd(ctx, service, cmd):
    with ctx.cd(ROOT_DIR):
        ctx.run('docker-compose -f {} exec -T {} bash -c "{}"'.format(get_compose_file(), service, cmd))


@task
def dcx(ctx, service):
    with ctx.cd(ROOT_DIR):
        ctx.run('docker-compose -f {} exec {} bash'.format(get_compose_file(), service), pty=True)


@task(optional=['service'])
def dcup(ctx, service=None):
    with ctx.cd(ROOT_DIR):
        if service:
            ctx.run('docker-compose -f {} up -d --build {}'.format(get_compose_file(), service))
        else:
            ctx.run('docker-compose -f {} up -d --build'.format(get_compose_file()))


@task(optional=['service'])
def dcs(ctx, service=None):
    with ctx.cd(ROOT_DIR):
        if service:
            ctx.run('docker-compose -f {} stop {}'.format(get_compose_file(), service))
        else:
            ctx.run('docker-compose -f {} stop'.format(get_compose_file()))


@task
def dcd(ctx):
    with ctx.cd(ROOT_DIR):
        ctx.run('docker-compose -f {} down --remove-orphans'.format(get_compose_file()))

@task
def dcps(ctx):
    with ctx.cd(ROOT_DIR):
        ctx.run('docker-compose -f {} ps'.format(get_compose_file()))

@task
def aws_ec2_ls(ctx):
    ctx.run('./tools/aws-ec2-list.py')


# download a specific env file from S3
@task
def pull_envfile(ctx, env, output=None):
    output = output if output else '.env'
    if os.path.exists(output):
        os.unlink(output)
    s3_vault_uri = 's3://build.vault'
    if env == 'prod':
        ctx.run('aws s3 cp {}/.env.prod {}'.format(s3_vault_uri, output))
    elif env == 'staging':
        ctx.run('aws s3 cp {}/.env.staging {}'.format(s3_vault_uri, output))
    elif env == 'dev':
        ctx.run('ln -s .env.dist {}'.format(output))
    else:
        print('unknown env name : {}'.format(env))
        sys.exit()

@task
def print_jwt(ctx):
    print(os.getenv('JWT_APP'))

@task
def print_env(ctx):
    print(os.getenv('ENV'))

@task
def api_task_run(ctx, task_name):
    jwt = os.getenv('JWT_APP')
    scheme = os.getenv('API_SCHEME')
    host = os.getenv('API_HOST')
    port = os.getenv('API_PORT')
    api_base_url = '{}://{}:{}'.format(scheme, host, port)
    header = {
        'Authorization': 'Bearer {}'.format(jwt)
    }
    data = {}
    url = '{}/api/v1/tasks/{}'.format(api_base_url, task_name)
    print(url)
    response = requests.post(url, json=data, headers=header)
    print(response)
    print(response.json())




# ecs-cli configure --region eu-west-1 --cluster TicketChainerCluster


# @task
# def docker_build(ctx, service):
#     #docker build -t nodejs . --build-arg UID=500 --build-arg GID=500

@task()
def deploy(ctx, env, app):
    pull_envfile(ctx, env)
    gitclone(ctx, 'docker-compose')
    gitclone(ctx, app)
    dcup(ctx)
