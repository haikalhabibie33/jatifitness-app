import { BasePage } from "#pages/base.page.js";
import { LocatorType } from "#types/locator.js";
import TableUtils from "#utils/table.utils.js";
import { APIRequestContext, Page } from "@playwright/test";
import { CheckInTableConfig } from "./check-in-table";

export default class CheckInPage extends BasePage {

    private readonly inputField: LocatorType;
    private readonly loadButton: LocatorType;
    private readonly scanQRButton: LocatorType;
    private readonly stopScanQRButton: LocatorType;

    private readonly checkInOutTable: TableUtils<
        typeof CheckInTableConfig.columns,
        typeof CheckInTableConfig.actions
        >

    constructor(page:Page, request:APIRequestContext){
        super(page,request);
        this.inputField = {value:'',description:''};
        this.loadButton = {value:'',description:''};
        this.scanQRButton = {value:'',description:''};
        this.stopScanQRButton = {value:'',description:''};

        this.checkInOutTable = new TableUtils(page,CheckInTableConfig);
    }

    private async inputTokenIntoField(token: string): Promise<void> {
        await this.elements.fillElement(this.inputField, token);
    }

    private async clickLoadButton(): Promise<void> {
        await this.elements.clickElement(this.loadButton);
    }

    public async checkIn(): Promise<void> {
        const token = '' // need API utils to get token later
        await this.inputTokenIntoField(token);
        await this.clickLoadButton();
    }
}