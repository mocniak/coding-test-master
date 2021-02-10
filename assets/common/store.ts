import {
    useDispatch as useDispatchBase,
    useSelector as useSelectorBase,
} from "react-redux";
import { combineReducers, configureStore } from "@reduxjs/toolkit";
import { securityReducer } from "../security/store";
import { classesReducer } from "../classes/store";

const reducer = combineReducers({
    security: securityReducer,
    classes: classesReducer,
});

const store = configureStore({ reducer });

export type RootState = any;

type AppDispatch = typeof store.dispatch;

export const useDispatch = () => useDispatchBase<AppDispatch>();

export const useSelector = <TSelected = unknown>(
    selector: (state: RootState) => TSelected
): TSelected => useSelectorBase<RootState, TSelected>(selector);

export default store;
