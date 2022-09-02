import "./shared/page";
import DocumentHandler from "./shared/DocumentHandler";
import Options from "./filter/Options";
import SearchHandler from "./search-page/SearchHandler";

DocumentHandler.whenReady(Options.createFilter);

Options.onSubmit = () => {
  window.history.replaceState(null, "", Options.toGetParams());

  SearchHandler.itemsElement.innerHTML = "";
  SearchHandler.getItems();
};

DocumentHandler.whenReady(() => {
  SearchHandler.resizeHandler();
  SearchHandler.getItems();
});

window.addEventListener("resize", SearchHandler.resizeHandler);
