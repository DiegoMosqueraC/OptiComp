#!/bin/bash

# ============================================================
# OptiComp - Pruebas de Integración (Actividad 3 - Guía 10)
# Uso: bash scripts/pruebas_integracion.sh <IP_SERVIDOR>
# Ejemplo: bash scripts/pruebas_integracion.sh 192.168.1.10
# ============================================================

IP="${1:-localhost}"
BASE="http://${IP}"
SOAP_URL="${BASE}/soap/server.php"
XML_URL="${BASE}/api/xml"
LOG_FILE="logs/pruebas_guia10_$(date +%Y%m%d_%H%M%S).log"

mkdir -p logs

PASS=0
FAIL=0

log() {
    echo "$1" | tee -a "$LOG_FILE"
}

test_result() {
    local nombre="$1"
    local codigo="$2"
    local esperado="$3"
    local body="$4"

    if [ "$codigo" -eq "$esperado" ] && ([ -z "$5" ] || echo "$body" | grep -q "$5"); then
        log "  [PASS] ${nombre}"
        PASS=$((PASS+1))
    else
        log "  [FAIL] ${nombre} — HTTP ${codigo}, esperado ${esperado}"
        FAIL=$((FAIL+1))
    fi
}

log ""
log "============================================================"
log "  OptiComp - Suite de Pruebas de Integracion (Guia 10)"
log "  Servidor: ${IP}"
log "  Fecha: $(date)"
log "============================================================"
log ""

# ── BLOQUE 1: Conectividad base ──────────────────────────────
log "=== BLOQUE 1: Conectividad HTTP (Apache corriendo) ==="
CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 "${BASE}/")
test_result "GET / (Home)" "$CODE" 200
log ""

# ── BLOQUE 2: WSDL disponible ────────────────────────────────
log "=== BLOQUE 2: WSDL publicado ==="
RESP=$(curl -s --max-time 5 "${SOAP_URL}?wsdl")
CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 "${SOAP_URL}?wsdl")
test_result "GET WSDL (HTTP 200)" "$CODE" 200
echo "$RESP" | grep -q "definitions" && { log "  [PASS] WSDL contiene <definitions>"; PASS=$((PASS+1)); } \
    || { log "  [FAIL] WSDL no contiene <definitions>"; FAIL=$((FAIL+1)); }
echo "$RESP" | grep -q "pingServidor" && { log "  [PASS] WSDL contiene operacion pingServidor"; PASS=$((PASS+1)); } \
    || { log "  [FAIL] WSDL no contiene pingServidor"; FAIL=$((FAIL+1)); }
echo "$RESP" | grep -q "consultarProductos" && { log "  [PASS] WSDL contiene operacion consultarProductos"; PASS=$((PASS+1)); } \
    || { log "  [FAIL] WSDL falta consultarProductos"; FAIL=$((FAIL+1)); }
log ""

# ── BLOQUE 3: Consumo SOAP - pingServidor ────────────────────
log "=== BLOQUE 3: SOAP - pingServidor ==="
SOAP_PING='<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                   xmlns:ns1="urn:OptiCompService">
  <SOAP-ENV:Body>
    <ns1:pingServidor/>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>'

RESP=$(curl -s --max-time 10 \
    -X POST "$SOAP_URL" \
    -H "Content-Type: text/xml; charset=UTF-8" \
    -H "SOAPAction: urn:OptiCompService#pingServidor" \
    --data "$SOAP_PING")
CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 10 \
    -X POST "$SOAP_URL" \
    -H "Content-Type: text/xml; charset=UTF-8" \
    -H "SOAPAction: urn:OptiCompService#pingServidor" \
    --data "$SOAP_PING")
test_result "SOAP POST pingServidor (HTTP 200)" "$CODE" 200
echo "$RESP" | grep -q "SOAP-ENV:Envelope" && { log "  [PASS] Response es Envelope SOAP valido"; PASS=$((PASS+1)); } \
    || { log "  [FAIL] Response no es Envelope SOAP"; FAIL=$((FAIL+1)); }
echo "$RESP" | grep -q "OptiComp SOAP Server OK" && { log "  [PASS] pingServidor retorna mensaje correcto"; PASS=$((PASS+1)); } \
    || { log "  [FAIL] pingServidor respuesta incorrecta"; FAIL=$((FAIL+1)); }
log "  Response: $(echo $RESP | head -c 300)"
log ""

# ── BLOQUE 4: SOAP - consultarProductos ─────────────────────
log "=== BLOQUE 4: SOAP - consultarProductos ==="
SOAP_PROD='<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                   xmlns:ns1="urn:OptiCompService">
  <SOAP-ENV:Body>
    <ns1:consultarProductos/>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>'

RESP=$(curl -s --max-time 10 \
    -X POST "$SOAP_URL" \
    -H "Content-Type: text/xml; charset=UTF-8" \
    -H "SOAPAction: urn:OptiCompService#consultarProductos" \
    --data "$SOAP_PROD")
CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 10 \
    -X POST "$SOAP_URL" \
    -H "Content-Type: text/xml; charset=UTF-8" \
    -H "SOAPAction: urn:OptiCompService#consultarProductos" \
    --data "$SOAP_PROD")
test_result "SOAP POST consultarProductos (HTTP 200)" "$CODE" 200
echo "$RESP" | grep -q "SOAP-ENV:Body" && { log "  [PASS] Response contiene SOAP Body"; PASS=$((PASS+1)); } \
    || { log "  [FAIL] Response no contiene SOAP Body"; FAIL=$((FAIL+1)); }
log ""

# ── BLOQUE 5: API XML (REST-XML, Guia 7) ─────────────────────
log "=== BLOQUE 5: API XML - consultarProductos ==="
XML_REQ='<?xml version="1.0" encoding="UTF-8"?>
<request><operacion>consultarProductos</operacion></request>'

RESP=$(curl -s --max-time 10 \
    -X POST "$XML_URL" \
    -H "Content-Type: text/xml" \
    --data "$XML_REQ")
CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 10 \
    -X POST "$XML_URL" \
    -H "Content-Type: text/xml" \
    --data "$XML_REQ")
test_result "XML POST consultarProductos (HTTP 200)" "$CODE" 200
echo "$RESP" | grep -q "<status>OK</status>" && { log "  [PASS] API XML retorna status OK"; PASS=$((PASS+1)); } \
    || { log "  [FAIL] API XML no retorna OK"; FAIL=$((FAIL+1)); }
log ""

# ── BLOQUE 6: Prueba de estabilidad (10 peticiones) ──────────
log "=== BLOQUE 6: Estabilidad - 10 peticiones concurrentes a pingServidor ==="
EXITOS=0
for i in $(seq 1 10); do
    CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 \
        -X POST "$SOAP_URL" \
        -H "Content-Type: text/xml; charset=UTF-8" \
        -H "SOAPAction: urn:OptiCompService#pingServidor" \
        --data "$SOAP_PING")
    if [ "$CODE" -eq 200 ]; then
        EXITOS=$((EXITOS+1))
    fi
done
log "  Peticiones exitosas: ${EXITOS}/10"
[ "$EXITOS" -ge 9 ] && { log "  [PASS] Servidor estable (>=90% exito)"; PASS=$((PASS+1)); } \
    || { log "  [FAIL] Servidor inestable (<90% exito)"; FAIL=$((FAIL+1)); }
log ""

# ── BLOQUE 7: Registro UDDI disponible ───────────────────────
log "=== BLOQUE 7: Registro UDDI accesible ==="
CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 "${BASE}/soap/uddi_registry.json")
test_result "GET uddi_registry.json (HTTP 200)" "$CODE" 200
log ""

# ── Resumen final ─────────────────────────────────────────────
TOTAL=$((PASS+FAIL))
log "============================================================"
log "  RESUMEN FINAL"
log "  Total pruebas: ${TOTAL}"
log "  PASS: ${PASS}"
log "  FAIL: ${FAIL}"
log "  Log guardado en: ${LOG_FILE}"
log "============================================================"

[ "$FAIL" -eq 0 ] && log "  RESULTADO: TODAS LAS PRUEBAS PASARON" \
    || log "  RESULTADO: HAY ${FAIL} PRUEBA(S) FALLIDAS"
log ""
