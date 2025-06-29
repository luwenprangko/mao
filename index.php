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
            <section id="personalInfo">
                <input type="text" name="firstName" id="firstName" placeholder="First Name"><br>
                <input type="text" name="lastName" id="lastName" placeholder="Last Name"><br>

                <label for="option">Select Role:</label>
                <select name="option" id="option">
                    <option value="personal">Personal</option>
                    <option value="relative">w/ Relative</option>
                </select><br>

                <input type="text" name="relativeName" id="relativeName" placeholder="Relative Name"
                    style="display: none;">
                <input type="text" name="relationship" id="relationship" placeholder="Relationship (e.g. Brother)"
                    style="display: none;"><br>

                <button type="button" id="nextBtn">Next</button>
            </section>

            <!-- Account Info -->
            <section id="accountInfo" style="display: none;">
                <input type="email" name="email" id="email" placeholder="Email"><br>
                <input type="password" name="password" id="password" placeholder="Password"><br>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password"><br>
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
        const firstName = document.getElementById('firstName');
        const lastName = document.getElementById('lastName');

        option.addEventListener('change', function () {
            const isRelative = this.value === 'relative';
            relativeName.style.display = isRelative ? 'block' : 'none';
            relationship.style.display = isRelative ? 'block' : 'none';
        });

        nextBtn.addEventListener('click', function () {
            let isValid = true;
            const role = option.value;

            // Reset borders
            [firstName, lastName, relativeName, relationship].forEach(input => {
                input.style.border = '';
            });

            if (!firstName.value.trim()) {
                firstName.style.border = '1px solid red';
                isValid = false;
            }

            if (!lastName.value.trim()) {
                lastName.style.border = '1px solid red';
                isValid = false;
            }

            if (role === 'relative') {
                if (!relativeName.value.trim()) {
                    relativeName.style.border = '1px solid red';
                    isValid = false;
                }
                if (!relationship.value.trim()) {
                    relationship.style.border = '1px solid red';
                    isValid = false;
                }
            }

            if (isValid) {
                personalInfo.style.display = 'none';
                accountInfo.style.display = 'block';
            } else {
                alert('Please fill in all required fields.');
            }
        });

        backBtn.addEventListener('click', function () {
            accountInfo.style.display = 'none';
            personalInfo.style.display = 'block';
        });
    </script>
</body>

</html>