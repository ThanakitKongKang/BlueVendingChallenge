import React from "react";
import { expect } from "chai";
import { render } from "react-dom";
import CoinInsertion from "../src/components/CoinInsertion";
let jsdom = require("mocha-jsdom");

global.document = jsdom({
  url: "http://localhost:3000/",
});

let rootContainer;

beforeEach(() => {
  rootContainer = document.createElement("div");
  document.body.appendChild(rootContainer);
});

afterEach(() => {
  document.body.removeChild(rootContainer);
  rootContainer = null;
});

describe("CoinInsertion Component", () => {
  it("should render input and button elements", () => {
    render(<CoinInsertion />, rootContainer);

    const p = rootContainer.querySelector("p");
    expect(p.textContent).contains("Insert Amount");
  });
});
