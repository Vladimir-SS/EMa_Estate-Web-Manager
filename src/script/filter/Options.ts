import FilterOption from "./FilterOption";
import DropdownFilterOption from "./DropdownFilterOption";
import SliderFilterOption from "./SliderFilterOption";
import SubmitFilterOption from "./SubmitFilterOption";
import DocumentHandler from "../shared/DocumentHandler";
import FilterOptionHandler from "./FilterOptionHandler";

class Options {
  public static onSubmit() {
    window.location.replace("/search" + this.toGetParams());
  }

  public static dropdown: { [key: string]: DropdownFilterOption } = {
    apType: DropdownFilterOption.createWithDefault(
      [
        "Decomandat",
        "Semidecomandat",
        "Nedecomandat",
        "Circular",
        "Open-Space",
      ],
      "Tip"
    ),
    type: new DropdownFilterOption(["Apartament", "Casă", "Office", "Teren"]),
    by: DropdownFilterOption.createWithDefault(
      ["Proprietar", "Agent Imobiliar"],
      "Oricine"
    ),
    transaction: new DropdownFilterOption(["Închiriere", "Cumpărare"]),
  };

  public static slider: { [key: string]: SliderFilterOption } = {
    rooms: new SliderFilterOption("Camere", "camere")
      .set(1, 5)
      .openRightDomain(),
    bathrooms: new SliderFilterOption("Băi", "băi").set(1, 3).openRightDomain(),
    price: new SliderFilterOption("Preț", "Lei"),
    builtIn: new SliderFilterOption("Anul Construcției", "")
      .set(1950, 2022)
      .openRightDomain()
      .openLeftDomain(),
  };

  //scris () => Options.onSubmit() inse schimba onSubmit-ul
  public static submitButton = new SubmitFilterOption(() => Options.onSubmit());

  private static changeAllOnType() {
    const typeDependent = [
      [
        Options.dropdown.apType,
        Options.slider.rooms,
        Options.slider.bathrooms,
        Options.slider.builtIn,
      ], //Apartment
      [Options.slider.rooms, Options.slider.bathrooms, Options.slider.builtIn], //House
      [Options.slider.bathrooms, Options.slider.builtIn], //Office
      [], //Land
    ];

    typeDependent.forEach((list) =>
      list.forEach((op) => (op.element.style.display = "none"))
    );
    let lastIndex = 0;
    Options.dropdown.type.onChange((index, __text) => {
      typeDependent[lastIndex].forEach(
        (op) => (op.element.style.display = "none")
      );
      typeDependent[index].forEach((op) =>
        op.element.style.removeProperty("display")
      );
      lastIndex = index;
    });
  }

  private static changePriceOnType() {
    const prices: [[number, number], [number, number]][] = [
      [
        [150, 2000],
        [50000, 400000],
      ], //apartament inchiriere, apartament cumparare
      [
        [500, 4000],
        [100000, 400000],
      ], //casa
      [
        [750, 5000],
        [200000, 500000],
      ], //office
      [
        [2000, 10000],
        [300000, 600000],
      ], //land
    ];

    const changeHandler = (__index: number, __text: string) => {
      const typeIndex = Options.dropdown.type.getOption().index;
      const transactionTypeIndex =
        Options.dropdown.transaction.getOption().index;
      const currentPrice = prices[typeIndex][transactionTypeIndex];
      Options.slider.price
        .set(...currentPrice)
        .openRightDomain()
        .openLeftDomain();
    };

    Options.dropdown.type.onChange(changeHandler);
    Options.dropdown.transaction.onChange(changeHandler);
  }

  private static addFilters() {
    //I want them in a specific order
    const options: FilterOption[] = [
      Options.dropdown.type,
      Options.dropdown.transaction,
      Options.dropdown.apType,
      Options.slider.price,
      Options.dropdown.by,
      Options.slider.rooms,
      Options.slider.bathrooms,
      Options.slider.builtIn,
      Options.submitButton,
    ];

    //overengineering :)) i changed that .add.add.add.add with this
    options.reduce(
      (handler, current) => handler.add(current),
      FilterOptionHandler
    );
  }

  public static toGetParams(): string {
    const dropdownParams = Object.entries(Options.dropdown)
      .filter(
        ([__key, op]) => op.element.style.display != "none" && op.isSelected()
      )
      .map(([key, op]) => `${key}=${op.getOption().index}`);

    const sliderParams = Object.entries(Options.slider)
      .filter(
        ([__key, op]) => op.element.style.display != "none" && op.isSelected()
      )
      .map(([key, op]) => {
        const [pos1, pos2] = op.getSliderPosition();
        return `${key}0=${pos1}&${key}1=${pos2}`;
      });

    return "?" + [...dropdownParams, ...sliderParams].join("&");
  }

  public static fromGetParams() {
    const GET = DocumentHandler.getGETParameters();

    Object.entries(Options.dropdown)
      .filter(([key, __op]) => GET[key] != null)
      .forEach(([key, op]) => op.chooseOption(Number(GET[key])));

    Object.entries(Options.slider).forEach(([key, op]) => {
      const pos1 = GET[key + 0],
        pos2 = GET[key + 1];

      if (pos1 != null && pos2 != null) op.setSliderPosition(pos1, pos2);
    });
  }

  public static createFilter() {
    Options.changeAllOnType();
    Options.changePriceOnType();
    Options.fromGetParams();
    Options.addFilters();
  }
}

declare global {
  type DropdownMap = { [key in keyof typeof Options.dropdown]: string[] };
}

export default Options;
