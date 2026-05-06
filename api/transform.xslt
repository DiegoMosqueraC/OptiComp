<?xml version="1.0"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
<html>
<body>
    <h2>Respuesta del servidor</h2>
    <p><b>Status:</b> <xsl:value-of select="response/status"/></p>
    <p><b>Mensaje:</b> <xsl:value-of select="response/mensaje"/></p>
</body>
</html>
</xsl:template>

</xsl:stylesheet>