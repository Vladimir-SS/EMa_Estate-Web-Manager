interface GETParameters {
  [key: string]: string;
}

class DocumentHandler {
  private static queue: (() => void)[] = [];

  public static whenReady = (func: () => void) => {
    if (DocumentHandler.documentIsReady()) func();
    else DocumentHandler.queue.push(func);
  };

  private static documentIsReady = () =>
    document.readyState === "complete" || document.readyState === "interactive";

  private static runPipeline = () => {
    DocumentHandler.queue.forEach((f) => f());
    DocumentHandler.queue = [];
  };

  public static ready = () => {
    let func = this.runPipeline;

    if (DocumentHandler.documentIsReady()) func();
    else document.addEventListener("DOMContentLoaded", func);
  };

  private static GETParamsToObj(params: string): GETParameters {
    return params
      .split("&")
      .map((p) => p.split("="))
      .reduce((prev, [key, op]) => {
        prev[key] = op;
        return prev;
      }, {});
  }

  public static getGETParameters() {
    //TODO: this is deprecated
    const params = window.location.search.substr(1);
    return params != null && params !== "" ? this.GETParamsToObj(params) : {};
  }
}

DocumentHandler.ready();

export default DocumentHandler;
