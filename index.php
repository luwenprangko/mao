<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Multi-Step Form</title>
</head>

<body>
    <main>
        <form id="mainForm" method="post">
            <!-- Personal Info -->
            <div>Register</div>
            <section id="personalInfo">
                <input type="text" name="firstName" id="firstName" placeholder="First Name" required>
                <input type="text" name="lastName" id="lastName" placeholder="Last Name" required>

                <label for="option">Select Role:</label>
                <select name="option" id="option">
                    <option value="personal">Personal</option>
                    <option value="relative">w/ Relative</option>
                </select>

                <input type="text" name="relativeName" id="relativeName" placeholder="Relative Name" hidden>
                <input type="text" name="relationship" id="relationship" placeholder="Relationship (e.g. Brother)"
                    hidden>

                <button type="button" id="nextBtn">Next</button>
            </section>

            <!-- Account Info -->
            <section id="accountInfo" hidden>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password"
                    required>

                <button type="button" id="backBtn">Back</button>
                <button type="submit">Register</button>
            </section>
        </form>
    </main>

    <script>
        const option = document.getElementById('option');
        const relativeName = document.getElementById('relativeName');
        const relationship = document.getElementById('relationship');
        const nextBtn = document.getElementById('nextBtn');
        const backBtn = document.getElementById('backBtn');
        const accountInfo = document.getElementById('accountInfo');
        const personalInfo = document.getElementById('personalInfo');

        option.addEventListener('change', function () {
            const isRelative = this.value === 'relative';
            relativeName.hidden = !isRelative;
            relationship.hidden = !isRelative;
            relativeName.required = isRelative;
            relationship.required = isRelative;
        });

        nextBtn.addEventListener('click', function () {
            const inputs = personalInfo.querySelectorAll('input:not([hidden]), select');
            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return;
                }
            }
            personalInfo.hidden = true;
            accountInfo.hidden = false;
        });

        backBtn.addEventListener('click', function () {
            accountInfo.hidden = true;
            personalInfo.hidden = false;
        });
    </script>
</body>

</html>