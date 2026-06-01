#!/bin/bash

# ============================================================
# OptiComp - Script de despliegue Ubuntu Server
# Guía 10 - Actividad 1
# Uso: sudo bash scripts/deploy_ubuntu.sh
# ============================================================

set -e

PROJECT_NAME="OptiComp"
WEB_ROOT="/var/www/html/${PROJECT_NAME}"
DB_NAME="db_opticomp"
DB_USER="root"

echo ""
echo "=============================================="
echo "  OptiComp - Despliegue en Ubuntu Server"
echo "  Guia Practica N10 - FESC 2026"
echo "=============================================="
echo ""

# ── 1. Actualizar sistema ────────────────────────────────────
echo "[1/7] Actualizando sistema..."
apt-get update -qq

# ── 2. Instalar Apache + PHP + extensiones ───────────────────
echo "[2/7] Instalando Apache2 + PHP 8.2..."
apt-get install -y -qq \
    apache2 \
    php8.2 \
    php8.2-mysql \
    php8.2-xml \
    php8.2-soap \
    php8.2-curl \
    php8.2-mbstring \
    libapache2-mod-php8.2 \
    mysql-server \
    unzip \
    curl

# ── 3. Habilitar módulos Apache ──────────────────────────────
echo "[3/7] Habilitando modulos Apache..."
a2enmod rewrite
a2enmod php8.2

# ── 4. Copiar proyecto ───────────────────────────────────────
echo "[4/7] Publicando proyecto en ${WEB_ROOT}..."
if [ -d "$WEB_ROOT" ]; then
    rm -rf "$WEB_ROOT"
fi
mkdir -p "$WEB_ROOT"
cp -r . "$WEB_ROOT/"
chown -R www-data:www-data "$WEB_ROOT"
chmod -R 755 "$WEB_ROOT"
chmod -R 777 "$WEB_ROOT/logs"

# ── 5. Configurar VirtualHost Apache ────────────────────────
echo "[5/7] Configurando VirtualHost Apache..."
cat > /etc/apache2/sites-available/opticomp.conf << 'VHOST'
<VirtualHost *:80>
    ServerName opticomp.local
    DocumentRoot /var/www/html/OptiComp/public

    <Directory /var/www/html/OptiComp/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    <Directory /var/www/html/OptiComp>
        Options -Indexes
        AllowOverride None
        Require all denied
    </Directory>

    # Permitir acceso publico a /public solamente
    <Directory /var/www/html/OptiComp/public>
        Require all granted
    </Directory>

    ErrorLog  ${APACHE_LOG_DIR}/opticomp_error.log
    CustomLog ${APACHE_LOG_DIR}/opticomp_access.log combined
</VirtualHost>
VHOST

a2ensite opticomp.conf
a2dissite 000-default.conf 2>/dev/null || true

# ── 6. Importar base de datos ────────────────────────────────
echo "[6/7] Importando base de datos..."
mysql -u "$DB_USER" < "${WEB_ROOT}/database/db_opticomp.sql"
echo "  BD ${DB_NAME} importada correctamente."

# ── 7. Configurar Firewall (UFW) ─────────────────────────────
echo "[7/7] Configurando firewall UFW..."
ufw allow 22/tcp    comment 'SSH'
ufw allow 80/tcp    comment 'HTTP Apache - OptiComp'
ufw allow 3306/tcp  comment 'MySQL local'
ufw --force enable

# ── Reiniciar servicios ──────────────────────────────────────
systemctl restart apache2
systemctl enable apache2
systemctl enable mysql

# ── Detectar IP y actualizar UDDI ───────────────────────────
SERVER_IP=$(hostname -I | awk '{print $1}')
cd "$WEB_ROOT"
bash scripts/actualizar_uddi.sh

echo ""
echo "=============================================="
echo "  Despliegue COMPLETADO"
echo "  IP del servidor: ${SERVER_IP}"
echo ""
echo "  Sistema web:  http://${SERVER_IP}/"
echo "  SOAP WSDL:    http://${SERVER_IP}/soap/server.php?wsdl"
echo "  API XML:      http://${SERVER_IP}/api/xml"
echo "  UDDI:         http://${SERVER_IP}/soap/uddi_registry.json"
echo "=============================================="
echo ""
echo "Verifique con: curl http://${SERVER_IP}/"
echo ""
