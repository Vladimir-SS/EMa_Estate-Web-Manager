class DocumentHandler {
    private static pipeline = [];


    public static whenReady = (func: () => void) => {
        if (DocumentHandler.documentIsReady())
            func();
        else
            DocumentHandler.pipeline.push(func);
    }


    private static documentIsReady = () => document.readyState === "complete" || document.readyState === "interactive";

    private static runPipeline = () => {
        DocumentHandler.pipeline.forEach(f => f());
        DocumentHandler.pipeline = [];
    }

    public static ready = () => {
        let func = this.runPipeline;

        if (DocumentHandler.documentIsReady())
            func();
        else
            document.addEventListener("DOMContentLoaded", func);

    }
}