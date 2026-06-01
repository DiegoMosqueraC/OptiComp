#!/bin/bash

# ============================================================
# OptiComp - Actualizar registro UDDI con IP real del servidor
# Guía 10 - Actividad 2
# Uso: bash scripts/actualizar_uddi.sh
# ============================================================

# Detectar IP de la interfaz de red principal (no loopback)
SERVER_IP=$(hostname -I | awk '{print $1}')

if [ -z "$SERVER_IP" ]; then
    echo "[ERROR] No se pudo detectar la IP del servidor."
    exit 1
fi

REGISTRY="public/soap/uddi_registry.json"
SOAP_URL="http://${SERVER_IP}/soap/server.php"
XML_URL="http://${SERVER_IP}/api/xml"
echo "=============================================="
echo "  OptiComp - Actualizacion Registro UDDI"
echo "  IP detectada: ${SERVER_IP}"
echo "=============================================="

# Reemplazar IP_DEL_SERVIDOR por la IP real en el JSON
sed -i "s|http://IP_DEL_SERVIDOR|http://${SERVER_IP}|g" "$REGISTRY"

echo "[OK] Registro UDDI actualizado en: ${REGISTRY}"
echo ""
echo "  WSDL endpoint: ${SOAP_URL}?wsdl"
echo "  XML endpoint:  ${XML_URL}"
echo ""
echo "Verifique desde el cliente con:"
echo "  curl http://${SERVER_IP}/OptiComp/public/soap/server.php?wsdl"
echo "=============================================="
