
async function loadAndAttachIcons() {
  const eyeRes = await fetch("/assets/icons/eye.svg");
  const eyeOffRes = await fetch("/assets/icons/eye-off.svg");
  const eyeSVG = await eyeRes.text();
  const eyeOffSVG = await eyeOffRes.text();
  attachIcons(eyeOffSVG, eyeSVG)
}

function attachIcons(eyeOff, eyeOn) {
  const passwordInputs = document.querySelectorAll("input[type=\"password\"]");
  passwordInputs.forEach(password => {
    const div = document.createElement("div");
    div.classList.add("toggler");
    div.innerHTML = eyeOn;
    // label > password
    //       > div > svg
    password.parentElement.appendChild(div);
    div.onclick = () => {
      if (password.type === "password") {
        password.type = "text"
        div.innerHTML = eyeOff;
      } else {
        password.type = "password"
        div.innerHTML = eyeOn;
      }
    };

  });
}

loadAndAttachIcons();