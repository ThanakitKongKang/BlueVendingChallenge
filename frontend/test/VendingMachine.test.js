import React from "react";
import VendingMachine from "../src/components/VendingMachine";
import { render } from "react-dom";
const { JSDOM } = require("jsdom");
const should = require("chai").should();

describe("VendingMachine Component", () => {
  let document;
  let container;

  beforeEach(() => {
    const dom = new JSDOM("<!DOCTYPE html><html><body></body></html>");
    document = dom.window.document;
    container = document.createElement("div");
    document.body.appendChild(container);
  });

  afterEach(() => {
    container.remove();
    container = null;
    document = null;
  });

  it("renders ItemSelection and CoinInsertion components", () => {
    render(<VendingMachine />, container);

    // Perform assertions to check the presence of ItemSelection and CoinInsertion
    const itemSelection = container.querySelector(".vending-item-selection");
    const coinInsertion = container.querySelector(".vending-coin-insertion");
    should.exist(itemSelection);
    should.exist(coinInsertion);
  });
});
