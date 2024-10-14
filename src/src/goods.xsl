<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>Item Catalog</title>
            </head>
            <body>
                <div class="mt-5">
                    <h3>Shoppping Cart</h3>
                    <table class="table">
                        <xsl:if test="count(items/item) &gt; 0">
                            <thead>
                                <tr>
                                    <th>Item Number</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Add</th>
                                </tr>
                            </thead>
                        </xsl:if>
                        <tbody>
                            <xsl:for-each select="items/item[quantity_total &gt; 0]">
                                <tr>
                                    <td><xsl:value-of select="item_number"/></td>
                                    <td><xsl:value-of select="item_name"/></td>
                                    <td>
                                        <xsl:choose>
                                            <xsl:when test="string-length(description) &gt; 20">
                                                <xsl:value-of select="substring(description, 1, 20)"/>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <xsl:value-of select="description"/>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </td>
                                    <td><xsl:value-of select="price"/></td>
                                    <td><xsl:value-of select="quantity_total"/></td>
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