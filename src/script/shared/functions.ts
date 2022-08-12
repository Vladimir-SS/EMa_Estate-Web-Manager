export function createSimpleElement<K extends keyof HTMLElementTagNameMap>(
  tagName: K,
  classes: string
): HTMLElementTagNameMap[K] {
  const container = document.createElement(tagName);
  container.setAttribute("class", classes);
  return container;
}

export const createIcon = (name: string) => {
  return createSimpleElement("span", `icon icon-${name}`);
};

export const parseMoney = (n: number) => {
  const s = n.toString();
  let rv = "";
  let l = 0;
  let u = s.length % 3;
  if (u == 0) u = 3;
  while (u <= s.length) {
    rv += s.slice(l, u) + " ";
    l = u;
    u += 3;
  }

  return rv.slice(0, -1);
};
