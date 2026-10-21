import { expect } from '@playwright/test';
import { DURATIONS } from './enum.utils';

export default class DateUtils {
  private parseYmd(dateStr: string): Date {
    const [year, month, day] = dateStr.split('-').map(Number);
    return new Date(Date.UTC(year, month - 1, day));
  }

  private diffInDays(startDate: string, endDate: string): number {
    const start = this.parseYmd(startDate);
    const end = this.parseYmd(endDate);

    const diffMs = end.getTime() - start.getTime();
    return diffMs / (1000 * 60 * 60 * 24);
  }

  public assertDurationInDays(startDate: string, endDate: string, days: number): void {
    expect(this.diffInDays(startDate, endDate)).toBe(days);
  }

  public assertDurationMonthly(startDate: string, endDate: string): void {
    expect(this.diffInDays(startDate, endDate)).toBe(Number(DURATIONS.MONTHLY));
  }

  public assertDurationQuarterly(startDate: string, endDate: string): void {
    expect(this.diffInDays(startDate, endDate)).toBe(Number(DURATIONS.QUARTERLY));
  }

  public assertDurationBiannually(startDate: string, endDate: string): void {
    expect(this.diffInDays(startDate, endDate)).toBe(Number(DURATIONS.BIANNUALLY));
  }

  public assertDurationYearly(startDate: string, endDate: string): void {
    expect(this.diffInDays(startDate, endDate)).toBe(Number(DURATIONS.YEARLY));
  }
}
