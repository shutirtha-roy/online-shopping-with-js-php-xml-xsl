<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/">
        <div class="container">
            <h2>Shopping Catalog</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Item Number</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity Available</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <xsl:apply-templates select="items/item[(quantity_available) > 0]"/>
                </tbody>
            </table>

            <h2>Shopping Cart</h2>
            <table class="table table-striped" id="cartTable">
                <thead>
                    <tr>
                        <th>Item Number</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cartBody">
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right;"><strong>Total:</strong></td>
                        <td colspan="2" id="cartTotal">$0</td>
                    </tr>
                </tfoot>
            </table>
            <button id="confirmPurchase" class="btn btn-success">Confirm Purchase</button>
            <button id="cancelPurchase" class="btn btn-danger">Cancel Purchase</button>
        </div>
    </xsl:template>

    <xsl:template match="item">
        <xsl:variable name="available_quantity" select="quantity_available"/>
        <tr>
            <td><xsl:value-of select="item_number"/></td>
            <td><xsl:value-of select="item_name"/></td>
            <td>
                <xsl:choose>
                    <xsl:when test="string-length(description) > 20">
                        <xsl:value-of select="substring(description, 1, 20)"/>...
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="description"/>
                    </xsl:otherwise>
                </xsl:choose>
            </td>
            <td>$<xsl:value-of select="price"/></td>
            <td><xsl:value-of select="$available_quantity"/></td>
            <td>
                <button class="btn btn-primary add-to-cart" data-item-number="{item_number}">
                    Add one to cart
                </button>
            </td>
        </tr>
    </xsl:template>
</xsl:stylesheet>