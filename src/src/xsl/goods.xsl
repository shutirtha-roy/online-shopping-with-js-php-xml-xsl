<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>lab08</title>
            </head>
            <body>
                <div class="mt-5">
                    <h3>Results</h3>
                    <table class="table table-striped">
                        <xsl:if test="count(items/item) &gt; 0">
                            <thead>
                                <tr>
                                    <td>Item Number</td>
                                    <td>Name</td>
                                    <td>Description</td>
                                    <td>Price</td>
                                    <td>Quantity</td>
                                    <td>Add</td>
                                </tr>
                            </thead>
                        </xsl:if>
                        <tbody>
                            <xsl:for-each select="items/item[quantity &gt; 0]">
                                <tr>
                                    <td><xsl:value-of select="number"></td>
                                    <td><xsl:value-of select="name"></td>
                                    <xsl:choose>
                                        <xsl:when test="count(description) > 0">
                                            <xsl:value-of select="substring(description/text(), 1, 20)">
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:value-of select="description">
                                        </xsl:otherwise>
                                    </xsl:choose>
                                    <td><xsl:value-of select="price"></td>
                                    <td><xsl:value-of select="quantity"></td>
                                    <td><button>Add one to cart</button></td>
                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>