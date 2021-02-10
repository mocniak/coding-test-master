import { User } from "../security/model";

export interface Class {
    id: number;
    startsAt: string;
    topic: string;
    students: User[];
    status: string;
}
