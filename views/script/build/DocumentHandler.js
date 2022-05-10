var _a;
class DocumentHandler {
}
_a = DocumentHandler;
DocumentHandler.pipeline = [];
DocumentHandler.whenReady = (func) => {
    if (DocumentHandler.documentIsReady())
        func();
    else
        DocumentHandler.pipeline.push(func);
};
DocumentHandler.documentIsReady = () => document.readyState === "complete" || document.readyState === "interactive";
DocumentHandler.runPipeline = () => {
    DocumentHandler.pipeline.forEach(f => f());
    DocumentHandler.pipeline = [];
};
DocumentHandler.ready = () => {
    let func = _a.runPipeline;
    if (DocumentHandler.documentIsReady())
        func();
    else
        document.addEventListener("DOMContentLoaded", func);
};
