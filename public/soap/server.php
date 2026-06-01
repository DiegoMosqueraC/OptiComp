<?php

/**
 * OptiComp - SoapServer (Actividad 2 - Guía 10)
 *
 * Publica los métodos remotos del MVP como servicio SOAP/WSDL.
 * URL: http://<IP_SERVIDOR>/OptiComp/public/soap/server.php
 *
 * Instrucciones de uso:
 *   - WSDL:   GET  ...server.php?wsdl
 *   - Invoke: POST ...server.php  (con body SOAP)
 */

define('BASE_PATH', dirname(__DIR__, 2));

// Autoload
spl_autoload_register(function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = BASE_PATH . '/app/';
    if (!str_starts_with($class, $prefix)) return;
    $file = $baseDir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) require $file;
});

require_once __DIR__ . '/OptiCompSoapService.php';

// Detectar IP/hostname real del servidor para el WSDL
$host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script  = $_SERVER['SCRIPT_NAME'];
$baseUri = 'http://' . $host . dirname($script);

$wsdlPath = __DIR__ . '/opticomp.wsdl';

// Generar WSDL dinámicamente con la IP real del servidor
$wsdlContent = generateWsdl($baseUri . '/server.php');
file_put_contents($wsdlPath, $wsdlContent);

// Publicar el servidor SOAP
$server = new SoapServer($wsdlPath, ['encoding' => 'UTF-8']);
$server->setClass('OptiCompSoapService');
$server->handle();

/**
 * Genera el WSDL dinámicamente apuntando al endpoint real.
 * Esto elimina dependencias de IPs fijas (requisito UDDI - Actividad 2).
 */
function generateWsdl(string $endpointUrl): string
{
    return <<<WSDL
<?xml version="1.0" encoding="UTF-8"?>
<definitions name="OptiCompService"
    targetNamespace="urn:OptiCompService"
    xmlns="http://schemas.xmlsoap.org/wsdl/"
    xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
    xmlns:tns="urn:OptiCompService"
    xmlns:xsd="http://www.w3.org/2001/XMLSchema">

  <!-- ========== TIPOS ========== -->
  <types>
    <xsd:schema targetNamespace="urn:OptiCompService">

      <xsd:complexType name="Producto">
        <xsd:sequence>
          <xsd:element name="id"          type="xsd:int"/>
          <xsd:element name="descripcion" type="xsd:string"/>
          <xsd:element name="categoria"   type="xsd:string"/>
        </xsd:sequence>
      </xsd:complexType>

      <xsd:complexType name="ProductoArray">
        <xsd:sequence>
          <xsd:element name="item" type="tns:Producto" minOccurs="0" maxOccurs="unbounded"/>
        </xsd:sequence>
      </xsd:complexType>

      <xsd:complexType name="Ticket">
        <xsd:sequence>
          <xsd:element name="id"            type="xsd:int"/>
          <xsd:element name="equipo"        type="xsd:string"/>
          <xsd:element name="descripcion"   type="xsd:string"/>
          <xsd:element name="estado"        type="xsd:string"/>
          <xsd:element name="fecha_ingreso" type="xsd:string"/>
        </xsd:sequence>
      </xsd:complexType>

      <xsd:complexType name="TicketArray">
        <xsd:sequence>
          <xsd:element name="item" type="tns:Ticket" minOccurs="0" maxOccurs="unbounded"/>
        </xsd:sequence>
      </xsd:complexType>

      <xsd:complexType name="RespuestaSimple">
        <xsd:sequence>
          <xsd:element name="status"  type="xsd:string"/>
          <xsd:element name="mensaje" type="xsd:string"/>
          <xsd:element name="id"      type="xsd:int"/>
        </xsd:sequence>
      </xsd:complexType>

    </xsd:schema>
  </types>

  <!-- ========== MENSAJES ========== -->

  <!-- consultarProductos -->
  <message name="consultarProductosRequest"/>
  <message name="consultarProductosResponse">
    <part name="return" type="tns:ProductoArray"/>
  </message>

  <!-- registrarProducto -->
  <message name="registrarProductoRequest">
    <part name="descripcion"  type="xsd:string"/>
    <part name="categoria_id" type="xsd:int"/>
  </message>
  <message name="registrarProductoResponse">
    <part name="return" type="tns:RespuestaSimple"/>
  </message>

  <!-- consultarTickets -->
  <message name="consultarTicketsRequest"/>
  <message name="consultarTicketsResponse">
    <part name="return" type="tns:TicketArray"/>
  </message>

  <!-- registrarTicket -->
  <message name="registrarTicketRequest">
    <part name="cliente_id"  type="xsd:int"/>
    <part name="equipo"      type="xsd:string"/>
    <part name="descripcion" type="xsd:string"/>
    <part name="estado"      type="xsd:string"/>
  </message>
  <message name="registrarTicketResponse">
    <part name="return" type="tns:RespuestaSimple"/>
  </message>

  <!-- pingServidor -->
  <message name="pingServidorRequest"/>
  <message name="pingServidorResponse">
    <part name="return" type="xsd:string"/>
  </message>

  <!-- ========== PORT TYPE ========== -->
  <portType name="OptiCompPortType">

    <operation name="consultarProductos">
      <input  message="tns:consultarProductosRequest"/>
      <output message="tns:consultarProductosResponse"/>
    </operation>

    <operation name="registrarProducto">
      <input  message="tns:registrarProductoRequest"/>
      <output message="tns:registrarProductoResponse"/>
    </operation>

    <operation name="consultarTickets">
      <input  message="tns:consultarTicketsRequest"/>
      <output message="tns:consultarTicketsResponse"/>
    </operation>

    <operation name="registrarTicket">
      <input  message="tns:registrarTicketRequest"/>
      <output message="tns:registrarTicketResponse"/>
    </operation>

    <operation name="pingServidor">
      <input  message="tns:pingServidorRequest"/>
      <output message="tns:pingServidorResponse"/>
    </operation>

  </portType>

  <!-- ========== BINDING ========== -->
  <binding name="OptiCompBinding" type="tns:OptiCompPortType">
    <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>

    <operation name="consultarProductos">
      <soap:operation soapAction="urn:OptiCompService#consultarProductos"/>
      <input><soap:body use="encoded" namespace="urn:OptiCompService"
             encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="urn:OptiCompService"
              encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>

    <operation name="registrarProducto">
      <soap:operation soapAction="urn:OptiCompService#registrarProducto"/>
      <input><soap:body use="encoded" namespace="urn:OptiCompService"
             encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="urn:OptiCompService"
              encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>

    <operation name="consultarTickets">
      <soap:operation soapAction="urn:OptiCompService#consultarTickets"/>
      <input><soap:body use="encoded" namespace="urn:OptiCompService"
             encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="urn:OptiCompService"
              encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>

    <operation name="registrarTicket">
      <soap:operation soapAction="urn:OptiCompService#registrarTicket"/>
      <input><soap:body use="encoded" namespace="urn:OptiCompService"
             encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="urn:OptiCompService"
              encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>

    <operation name="pingServidor">
      <soap:operation soapAction="urn:OptiCompService#pingServidor"/>
      <input><soap:body use="encoded" namespace="urn:OptiCompService"
             encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="urn:OptiCompService"
              encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>

  </binding>

  <!-- ========== SERVICE ========== -->
  <service name="OptiCompService">
    <port name="OptiCompPort" binding="tns:OptiCompBinding">
      <soap:address location="{$endpointUrl}"/>
    </port>
  </service>

</definitions>
WSDL;
}
