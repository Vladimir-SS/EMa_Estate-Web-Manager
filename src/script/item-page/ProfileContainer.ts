import { createSimpleElement } from "../shared/functions";

declare global {
  interface ProfileData {
    id: number;
    lastName: string;
    firstName: string;
    businessName: string;
    createdAt: string;
    phone: string;
    imageURL: string;
  }
}

class ProfileContainer {
  public static create(data: ProfileData) {
    const profileContainer = createSimpleElement(
      "div",
      "content__box content__box--profile"
    );
    const { imageURL, lastName, firstName, businessName, createdAt, phone } =
      data;
    const avatarContainer = createSimpleElement(
      "div",
      "content__box--profile__avatar"
    );
    avatarContainer.style.backgroundImage = `url("${imageURL}")`;

    const nameElement = createSimpleElement("h2", "");
    nameElement.textContent =
      lastName +
      " " +
      firstName +
      (businessName == null ? "" : " (" + businessName + ")");

    const typeElement = createSimpleElement("p", "");
    typeElement.textContent =
      businessName == null ? "Persoana fizică" : "Agent Imobiliar";

    const joinedElement = createSimpleElement("p", "secondary");
    joinedElement.textContent = `Pe Estence din ${createdAt}`;

    const phoneContainer = createSimpleElement(
      "div",
      "label label--flex label--important"
    );
    phoneContainer.setAttribute("onclick", "");
    phoneContainer.onclick = () => {
      const callTo = "tel:" + phone;
      console.log(callTo);

      window.open(callTo);
    };
    phoneContainer.appendChild(createSimpleElement("span", "icon icon-phone"));
    phoneContainer.appendChild(document.createTextNode(phone));

    profileContainer.append(
      avatarContainer,
      nameElement,
      typeElement,
      joinedElement,
      phoneContainer
    );

    return profileContainer;
  }
}

export default ProfileContainer;
