<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
        <head>
            <title>Detalle del Ticket</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
                th { background-color: #4CAF50; color: white; }
            </style>
        </head>
        <body>
            <h2>Resumen del Ticket (Transformado con XSLT)</h2>
            <table>
                <tr>
                    <th>Cliente ID</th>
                    <th>Equipo</th>
                    <th>Descripción</th>
                    <th>Fecha de Ingreso</th>
                </tr>
                <tr>
                    <td><xsl:value-of select="Mensaje/Datos/Ticket/ClienteId"/></td>
                    <td><xsl:value-of select="Mensaje/Datos/Ticket/Equipo"/></td>
                    <td><xsl:value-of select="Mensaje/Datos/Ticket/Descripcion"/></td>
                    <td><xsl:value-of select="Mensaje/Datos/Ticket/FechaIngreso"/></td>
                </tr>
            </table>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>