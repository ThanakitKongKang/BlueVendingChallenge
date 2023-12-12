import React from 'react'
import ItemSelection from "../src/components/ItemSelection";
import { render } from "react-dom";
const { expect } = require("chai");
const should = require('chai').should();
const { JSDOM } = require("jsdom");

describe("ItemSelection Component Rendering", () => {
  const items = {
    data: [
      {
        id: 1,
        name: "Item 1",
        price: 10,
        amount: 5,
      },
      {
        id: 2,
        name: "Item 2",
        price: 20,
        amount: 8,
      },
    ],
  };

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

  it("renders the item list correctly", () => {
    render(
      <ItemSelection items={items} onItemSelect={() => null} />,
      container
    );

    const itemList = container.querySelector("ul");
    should.exist(itemList);

    const listItems = container.querySelectorAll("li");
    expect(listItems.length).equals(items.data.length);

    const selectButtons = container.querySelectorAll("button");
    expect(selectButtons.length).equals(items.data.length);
  });
});
