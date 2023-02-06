<form action="" method="post">
    <label for="courier">Select Carrier:</label>
    <select id="courier" name="courier">
        <option value="">Select</option>
        <option value="fedex">FedEx</option>
        <option value="dhl">DHL</option>
        <option value="ups">UPS</option>
        <option value="aramex">Aramex</option>
        <option value="redstarexpress">Red Star Express</option>
        <!-- Add more carriers if needed -->
    </select>

    <label for="tracking-number">Enter Tracking Number:</label>
    <input type="text" id="tracking-number" name="tracking-number">

    <input type="submit" value="Submit">
</form>
